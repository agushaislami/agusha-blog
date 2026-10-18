<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

class HtmlSanitizer
{
    private const ALLOWED_TAGS = [
        'a', 'blockquote', 'br', 'em', 'figcaption', 'figure', 'h2', 'h3', 'img',
        'li', 'ol', 'p', 's', 'strong', 'u', 'ul',
    ];

    public function sanitize(string $html): string
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $previousErrorMode = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<?xml encoding="utf-8" ?><div id="rich-content-root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD,
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previousErrorMode);

        $root = $document->getElementById('rich-content-root');

        if (! $root) {
            return '';
        }

        $this->sanitizeChildren($root);

        $sanitizedHtml = '';

        foreach ($root->childNodes as $child) {
            $sanitizedHtml .= $document->saveHTML($child);
        }

        return $sanitizedHtml;
    }

    private function sanitizeChildren(DOMNode $parent): void
    {
        foreach (iterator_to_array($parent->childNodes) as $child) {
            if (! $child instanceof DOMElement) {
                if ($child->nodeType !== XML_TEXT_NODE) {
                    $parent->removeChild($child);
                }

                continue;
            }

            $tagName = strtolower($child->tagName);

            if (! in_array($tagName, self::ALLOWED_TAGS, true)) {
                if (in_array($tagName, ['script', 'style', 'iframe', 'object', 'svg', 'math'], true)) {
                    $parent->removeChild($child);
                } else {
                    $this->sanitizeChildren($child);

                    while ($child->firstChild) {
                        $parent->insertBefore($child->firstChild, $child);
                    }

                    $parent->removeChild($child);
                }

                continue;
            }

            foreach (iterator_to_array($child->attributes) as $attribute) {
                $attributeName = strtolower($attribute->name);
                $allowedAttribute = ($tagName === 'a' && $attributeName === 'href')
                    || ($tagName === 'img' && in_array($attributeName, ['src', 'alt', 'class'], true));

                if (! $allowedAttribute) {
                    $child->removeAttributeNode($attribute);

                    continue;
                }

                if (in_array($attributeName, ['href', 'src'], true) && ! $this->isSafeUrl($attribute->value, $tagName === 'img')) {
                    $child->removeAttributeNode($attribute);
                }

                if ($tagName === 'img' && $attributeName === 'class') {
                    $allowedClasses = [
                        'article-image',
                        'article-image-small',
                        'article-image-medium',
                        'article-image-full',
                        'article-image-align-left',
                        'article-image-align-center',
                        'article-image-align-right',
                    ];
                    $imageClasses = array_intersect(preg_split('/\\s+/', trim($attribute->value)) ?: [], $allowedClasses);

                    if ($imageClasses === []) {
                        $child->removeAttributeNode($attribute);
                    } else {
                        $child->setAttribute('class', implode(' ', $imageClasses));
                    }
                }

            }

            if ($tagName === 'img' && ! $child->hasAttribute('src')) {
                $parent->removeChild($child);

                continue;
            }

            $this->sanitizeChildren($child);
        }
    }

    private function isSafeUrl(string $url, bool $isImage): bool
    {
        $url = trim($url);
        $scheme = parse_url($url, PHP_URL_SCHEME);

        if ($scheme !== null) {
            return in_array(strtolower($scheme), ['http', 'https'], true);
        }

        if (str_starts_with($url, '//')) {
            return false;
        }

        return $isImage ? str_starts_with($url, '/storage/posts/') : str_starts_with($url, '/');
    }
}
