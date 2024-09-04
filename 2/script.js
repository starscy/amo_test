// js код, который в зависимости от выбранного значения поля Тип
// отражает разный набор полей на странице http://test.amopoint-dev.ru/testzz/testlist.html
// Должны отображаются только те поля в атрибуте name которых есть значение
// выбранного элемента списка.

function updateFields() {
    // Получаем выбранное значение из поля "Тип"
    const selectedType = document.querySelector('select[name="type_val"]').value;

    // Получаем все поля на странице
    const fields = document.querySelectorAll('input, select, textarea');

    // Скрываем все поля, кроме поля выбора "Тип"
    fields.forEach(field => {
        field.style.display = (field.name === 'type_val' || (field.name && field.name.includes(selectedType))) ? 'block' : 'none';
    });
}

// Добавляем обработчик события на изменение поля "Тип"
document.querySelector('select[name="type_val"]').addEventListener('change', updateFields);