$(document).ready(function() {
    // mostrar/esconder a pass
    $(".show-password").click(function() {
        // Selecionar o campo de entrada anterior ao ícone
        var input = $(this).prev('input');
        // Verificar 
        if (input.attr("type") === "password") {
            //mudar para texto
            input.attr("type", "text");
            $(this).text("visibility"); // Alterar ícone 
        } else {
            //  mudar para pass
            input.attr("type", "password");
            $(this).text("visibility_off"); // Alterar ícone 
        }
    });
});

$(function() {
    // Datas de início e fim permitidas para seleção
    var startDate = new Date(2024, 8, 15); // 15 de setembro de 2024
    var endDate = new Date(2025, 5, 15); // 15 de junho de 2025

    // Define os períodos de feriado
    var holidays = [
        { start: new Date(2024, 11, 18), end: new Date(2025, 0, 2) }, // 18 de dezembro de 2024 a 2 de janeiro de 2025
        { start: new Date(2025, 1, 12), end: new Date(2025, 1, 14) }, // 12 a 14 de fevereiro de 2025
        { start: new Date(2025, 2, 25), end: new Date(2025, 3, 5) } // 25 de março de 2025 a 5 de abril de 2025
    ];

    // Função para verificar se a data está em um feriado
    function isHoliday(date) {
        for (var i = 0; i < holidays.length; i++) {
            if (date >= holidays[i].start && date <= holidays[i].end) {
                return true;
            }
        }
        return false;
    }

    // Inicializa o Datepicker
    $("#calendar").datepicker({
        dateFormat: "dd/mm/yy", // Formato da data exibido
        minDate: 0, // Apenas datas futuras são permitidas
        beforeShowDay: function(date) {
            var day = date.getDay();
            // Verifica se é um dia de semana e não é um feriado
            if ((day != 0 && day != 6) && !isHoliday(date)) {
                return [true, ""]; // Dia selecionável
            } else {
                return [false, "", "Indisponível"]; // Dia não selecionável
            }
        },
        minDate: startDate, // Data mínima permitida
        maxDate: endDate // Data máxima permitida
    });

    // Validação adicional antes do envio do formulário
    $("form").submit(function(event) {
        var selectedDate = $("#calendar").datepicker("getDate");
        var valid = true;

        // Verifica se a data selecionada não está vazia e é um dia útil, não é feriado e está dentro do intervalo permitido
        if (selectedDate && (selectedDate.getDay() == 0 || selectedDate.getDay() == 6 || isHoliday(selectedDate) || selectedDate < startDate || selectedDate > endDate)) {
            valid = false;
        }

        if (!valid) {
            event.preventDefault(); // Impede o envio do formulário se a data não for válida
            alert("Por favor, selecione uma data válida dentro do intervalo permitido.");
        }
    });
});