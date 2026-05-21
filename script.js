(function () {
    'use strict';

    const settings = window.codeBlockSettings || {};
    const lineNumbersEnabled = settings.line_numbers !== 'off';
    const syntaxEnabled = settings.syntax !== 'off';
    const theme = settings.theme === 'dark' ? 'dark' : 'light';
    const copyText = settings.copy_text || 'Copy';
    const copiedText = settings.copied_text || 'Copied';
    const copyFailedText = settings.copy_failed_text || 'Copy failed';
    const copyLabel = settings.copy_label || 'Copy code to clipboard';
    const autoDetectLanguages = [
        'xml',
        'css',
        'javascript',
        'typescript',
        'php',
        'python',
        'json',
        'bash',
        'shell',
        'sql',
        'java',
        'cpp',
        'csharp',
        'ruby',
        'go',
        'markdown',
        'yaml'
    ];
    const languageAliases = {
        cplusplus: 'cpp',
        cs: 'csharp',
        csharp: 'csharp',
        html: 'xml',
        js: 'javascript',
        md: 'markdown',
        py: 'python',
        sh: 'bash',
        ts: 'typescript',
        yml: 'yaml'
    };

    function createLineNumbers(codeText) {
        const lineNumbers = document.createElement('div');
        const fragment = document.createDocumentFragment();
        const lines = codeText.split('\n');

        lineNumbers.className = 'line-numbers-rows';
        lineNumbers.setAttribute('aria-hidden', 'true');

        lines.forEach(function (_line, index) {
            const number = document.createElement('span');
            number.textContent = String(index + 1);
            fragment.appendChild(number);
        });

        lineNumbers.appendChild(fragment);

        return lineNumbers;
    }

    function copyWithFallback(text) {
        const textArea = document.createElement('textarea');

        textArea.value = text;
        textArea.setAttribute('readonly', 'readonly');
        textArea.className = 'simple-code-block-highlighter-copy-source';
        document.body.appendChild(textArea);
        textArea.select();

        try {
            document.execCommand('copy');
            return Promise.resolve();
        } catch (error) {
            return Promise.reject(error);
        } finally {
            textArea.remove();
        }
    }

    function copyCode(text) {
        if (window.navigator.clipboard && window.navigator.clipboard.writeText) {
            return window.navigator.clipboard.writeText(text);
        }

        return copyWithFallback(text);
    }

    function updateButtonState(button, message) {
        button.textContent = message;

        window.setTimeout(function () {
            button.textContent = copyText;
        }, 1600);
    }

    function normalizeLanguage(language) {
        const normalized = String(language || '')
            .toLowerCase()
            .replace(/^language-/, '')
            .replace(/^lang-/, '')
            .replace(/^brush:/, '')
            .replace(/[^a-z0-9+#-]/g, '');

        return languageAliases[normalized] || normalized;
    }

    function getLanguageFromElement(element) {
        if (!element || !element.classList) {
            return '';
        }

        const classes = Array.prototype.slice.call(element.classList);

        for (let index = 0; index < classes.length; index += 1) {
            const className = classes[index];
            const matched = className.match(/^(?:language-|lang-)([a-z0-9+#-]+)$/i);

            if (matched) {
                return normalizeLanguage(matched[1]);
            }
        }

        for (let index = 0; index < classes.length; index += 1) {
            const className = classes[index];

            if (className.indexOf('brush:') === 0) {
                return normalizeLanguage(className);
            }
        }

        return '';
    }

    function getLanguage(block, codeElement) {
        return getLanguageFromElement(codeElement) || getLanguageFromElement(block);
    }

    function getAvailableAutoDetectLanguages() {
        if (!window.hljs || !window.hljs.getLanguage) {
            return [];
        }

        return autoDetectLanguages.filter(function (language) {
            return window.hljs.getLanguage(language);
        });
    }

    function highlightCode(codeText, language) {
        if (!syntaxEnabled || !window.hljs) {
            return null;
        }

        const normalizedLanguage = normalizeLanguage(language);

        try {
            if (normalizedLanguage && window.hljs.getLanguage(normalizedLanguage)) {
                return {
                    html: window.hljs.highlight(codeText, {
                        language: normalizedLanguage,
                        ignoreIllegals: true
                    }).value,
                    language: normalizedLanguage
                };
            }

            const highlighted = window.hljs.highlightAuto(codeText, getAvailableAutoDetectLanguages());

            return {
                html: highlighted.value,
                language: highlighted.language || ''
            };
        } catch (error) {
            return null;
        }
    }

    function setCodeContent(codeContainer, codeText, language) {
        const highlighted = highlightCode(codeText, language);

        if (!highlighted) {
            codeContainer.textContent = codeText;
            return '';
        }

        codeContainer.innerHTML = highlighted.html;
        codeContainer.classList.add('hljs');

        return highlighted.language;
    }

    function enhanceBlock(block) {
        if (block.classList.contains('custom-code-block')) {
            return;
        }

        const originalCode = block.querySelector('code');
        const codeText = originalCode ? originalCode.textContent || '' : block.textContent || '';
        const language = getLanguage(block, originalCode);
        const codeContainer = document.createElement('code');
        const button = document.createElement('button');

        block.textContent = '';
        block.classList.add('custom-code-block', 'custom-code-block-' + theme);
        block.classList.toggle('no-line-numbers', !lineNumbersEnabled);

        codeContainer.className = 'custom-code-content';
        const detectedLanguage = setCodeContent(codeContainer, codeText, language);

        if (detectedLanguage) {
            block.setAttribute('data-language', detectedLanguage);
            codeContainer.classList.add('language-' + detectedLanguage);
        }

        if (lineNumbersEnabled) {
            block.appendChild(createLineNumbers(codeText));
        }

        block.appendChild(codeContainer);

        button.type = 'button';
        button.textContent = copyText;
        button.className = 'copy-button';
        button.setAttribute('aria-label', copyLabel);
        button.addEventListener('click', function () {
            copyCode(codeText)
                .then(function () {
                    updateButtonState(button, copiedText);
                })
                .catch(function () {
                    updateButtonState(button, copyFailedText);
                });
        });

        block.appendChild(button);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const blocks = document.querySelectorAll('pre.wp-block-code, pre.wp-block-preformatted');

        blocks.forEach(enhanceBlock);
    });
}());
