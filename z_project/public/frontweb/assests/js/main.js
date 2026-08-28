document.addEventListener('DOMContentLoaded', function() {
    const categoryTabs = document.querySelectorAll('.category-tab');
    const subcategoryItems = document.querySelectorAll('.subcategory-item');

    categoryTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const categoryId = tab.getAttribute('data-category-id');

            subcategoryItems.forEach(item => {
                if (item.getAttribute('data-category-id') === categoryId) {
                    item.style.display = 'block';
                    console.log(categoryId);
                } else {
                    item.style.display = 'block';
                }
            });
        });
    });
});