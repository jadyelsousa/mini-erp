(function($) {
    $.fn.formatBrazilianReal = function() {
        return this.each(function() {
            const $input = $(this);
            const MAX_DIGITS = 12;

            function formatValue(value) {
                if (!value || isNaN(value)) return '';
                return new Intl.NumberFormat('pt-BR', {
                    style: 'currency',
                    currency: 'BRL',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(value).replace(/R\$\s*/g, '');
            }

            function parseValue(formatted) {
                if (!formatted) return '';
                const cleaned = formatted.replace(/\./g, '').replace(',', '.');
                return parseFloat(cleaned) || '';
            }

            $input.val(formatValue(parseValue($input.val())));

            $input.on('input', function() {
                let rawValue = $input.val().replace(/\D/g, '');
                if (rawValue.length > MAX_DIGITS) {
                    rawValue = rawValue.slice(0, MAX_DIGITS);
                }
                $input.val(rawValue ? formatValue(parseFloat(rawValue) / 100) : '');
            });

            $input.on('blur', function() {
                $input.val(formatValue(parseValue($input.val())));
            });

            $input.closest('form').on('submit', function() {
                const numericValue = parseValue($input.val());
                $input.val(numericValue);
            });
        });
    };
})(jQuery);
