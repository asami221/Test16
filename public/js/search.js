document.addEventListener('DOMContentLoaded', function() {
    const searchButton = document.getElementById('searchButton');
    const searchResults = document.getElementById('searchResults');

   
    searchButton.addEventListener('click', function() {
        performSearch();
    });

   
    document.querySelectorAll('th[data-sort]').forEach(header => {
        header.addEventListener('click', function() {
            const column = this.getAttribute('data-sort');
            const currentOrder = this.getAttribute('data-order') || 'asc';
            const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';
            this.setAttribute('data-order', newOrder);
            performSearch(column, newOrder);
        });
    });

    function performSearch(sortColumn = 'id', sortOrder = 'asc') {
        const params = new URLSearchParams({
            query: document.getElementById('query').value,
            minPrice: document.getElementById('minPrice').value,
            maxPrice: document.getElementById('maxPrice').value,
            minStock: document.getElementById('minStock').value,
            maxStock: document.getElementById('maxStock').value,
            sortColumn: sortColumn,
            sortOrder: sortOrder
        });

        fetch(`/api/products/search?${params.toString()}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            displayResults(data);
        })
        .catch(error => console.error('検索エラー:', error));
    }

   
    function displayResults(results) {
        searchResults.innerHTML = '';
        if (results.length > 0) {
            results.forEach(result => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${result.id}</td>
                    <td><img src="${result.image_path}" alt="商品画像" style="max-width: 100px;"></td>
                    <td>${result.product_name}</td>
                    <td>¥${result.price}</td>
                    <td>${result.stock}</td>
                    <td>${result.company_name || 'N/A'}</td>
                `;
                searchResults.appendChild(row);
            });
        } else {
            searchResults.innerHTML = '<tr><td colspan="6">該当する商品が見つかりませんでした。</td></tr>';
        }
    }





    function addDeleteEventListeners() {
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
    
                if (confirm('削除しますか？')) {
                    const productId = form.getAttribute('data-id');
    
                    fetch(form.action, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        } else {
                            throw new Error('サーバーエラーにより削除に失敗しました。');
                        }
                    })
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            document.getElementById(`product-${productId}`).remove();
                            alert(data.success);
                        }
                    })
                    .catch(error => {
                        console.error('削除エラー:', error);
                        alert(error.message);
                    });
                }
            });
        });
    }
    
    addDeleteEventListeners();
});
