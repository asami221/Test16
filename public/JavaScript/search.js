document.addEventListener('DOMContentLoaded', function() {
    const searchButton = document.getElementById('searchButton');
    const searchResults = document.getElementById('searchResults');

    searchButton.addEventListener('click', function() {
        const params = new URLSearchParams({
            query: document.getElementById('query').value,
            minPrice: document.getElementById('minPrice').value,
            maxPrice: document.getElementById('maxPrice').value,
            minStock: document.getElementById('minStock').value,
            maxStock: document.getElementById('maxStock').value,
            sortColumn: 'id',
            sortOrder: 'desc'
        });

        fetch(`/api/products/search?${params.toString()}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => displayResults(data))
        .catch(error => console.error('検索エラー:', error));
    });

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
                    <td><button class="delete-button" data-id="${result.id}">削除</button></td>
                `;
                searchResults.appendChild(row);
            });
            addDeleteEventListeners();
        } else {
            searchResults.innerHTML = '<tr><td colspan="7">該当する商品が見つかりませんでした。</td></tr>';
        }
    }

    function addDeleteEventListeners() {
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                if (confirm('削除しますか？')) {
                    fetch(`/api/products/${productId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector(`tr[data-id="${productId}"]`).remove();
                        } else {
                            alert(data.error || '削除に失敗しました。');
                        }
                    })
                    .catch(error => console.error('削除エラー:', error));
                }
            });
        });
    }
});
