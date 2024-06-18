document.addEventListener('DOMContentLoaded', function() {
    const options = {
        line_numbers: 'on', // This should be dynamically set based on PHP options
        theme: 'light' // This should also be set based on PHP options
    };

    const blocks = document.querySelectorAll('pre.wp-block-code, pre.wp-block-preformatted');
    blocks.forEach(block => {
        block.className += ' custom-code-block';

        const codeContainer = document.createElement('div');
        codeContainer.className = 'custom-code-content';
        const codeText = block.innerText;
        codeContainer.textContent = codeText;
        block.innerText = '';

        if (options.line_numbers === 'on') {
            const lines = codeText.split('\n');
            const lineNumbers = document.createElement('div');
            lineNumbers.className = 'line-numbers-rows';
            lines.forEach((line, index) => {
                lineNumbers.innerHTML += (index + 1) + '<br>';
            });
            block.appendChild(lineNumbers);
        }

        block.appendChild(codeContainer);

        const button = document.createElement('button');
        button.textContent = 'Copy';
        button.className = 'copy-button';
        button.onclick = function() {
            navigator.clipboard.writeText(codeContainer.textContent);
            alert('Copied!');
        };
        block.appendChild(button);
    });
});
