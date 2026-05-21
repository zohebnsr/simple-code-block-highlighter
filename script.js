(function () {
    'use strict';

    const settings = window.codeBlockSettings || {};
    const lineNumbersEnabled = settings.line_numbers !== 'off';
    const theme = settings.theme === 'dark' ? 'dark' : 'light';
    const copyText = settings.copy_text || 'Copy';
    const copiedText = settings.copied_text || 'Copied';
    const copyFailedText = settings.copy_failed_text || 'Copy failed';
    const copyLabel = settings.copy_label || 'Copy code to clipboard';

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

    function enhanceBlock(block) {
        if (block.classList.contains('custom-code-block')) {
            return;
        }

        const codeText = block.textContent || '';
        const codeContainer = document.createElement('code');
        const button = document.createElement('button');

        block.textContent = '';
        block.classList.add('custom-code-block', 'custom-code-block-' + theme);
        block.classList.toggle('no-line-numbers', !lineNumbersEnabled);

        codeContainer.className = 'custom-code-content';
        codeContainer.textContent = codeText;

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
