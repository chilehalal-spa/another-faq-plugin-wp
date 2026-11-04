document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.afq-item').forEach(function (item) {
        var summary = item.querySelector(':scope > .afq-question');
        var answer = item.querySelector(':scope > .afq-answer');

        if (!summary || !answer) return;

        summary.addEventListener('click', function (e) {
            // Evitamos el toggle nativo (instantáneo) para poder animar la altura.
            e.preventDefault();

            if (item.hasAttribute('open')) {
                closeItem(item, answer);
                return;
            }

            var accordion = item.closest('.afq-accordion');
            if (accordion && accordion.dataset.exclusivo !== 'no') {
                accordion.querySelectorAll(':scope > .afq-item[open]').forEach(function (other) {
                    if (other !== item) closeItem(other, other.querySelector(':scope > .afq-answer'));
                });
            }

            openItem(item, answer);
        });
    });

    function openItem(item, answer) {
        item.setAttribute('open', '');
        answer.style.maxHeight = '0px';

        // Forzamos reflow para que el navegador registre el punto de partida antes de animar.
        answer.offsetHeight;
        answer.style.maxHeight = answer.scrollHeight + 'px';
    }

    function closeItem(item, answer) {
        answer.style.maxHeight = answer.scrollHeight + 'px';

        // Forzamos reflow para que la transición parta de una altura fija, no de "none".
        answer.offsetHeight;
        answer.style.maxHeight = '0px';

        answer.addEventListener('transitionend', function handler(e) {
            if (e.propertyName !== 'max-height') return;
            item.removeAttribute('open');
            answer.removeEventListener('transitionend', handler);
        });
    }
});
