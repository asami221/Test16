$(document).ready(function() {
   
    $("#productTable").tablesorter({
        theme: 'default',
        headers: {
            4: { sorter: false } 
        }
    });

   
    function addDeleteEventListeners() {
        $(document).on('submit', '.delete-form', function(event) {
            event.preventDefault();

            if (confirm('削除しますか？')) {
                const form = $(this);
                const productId = form.data('id');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST', 
                    data: form.serialize(), 
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(data) {
                        // 成功時の処理
                        $(`#product-${productId}`).fadeOut(300, function() {
                            $(this).remove();
                            $("#productTable").trigger("update"); 
                        });
                        alert(data.success);
                    },
                    error: function(xhr) {
                        // エラー時の処理
                        console.error('削除エラー:', xhr.responseText);
                    }
                });
            }
        });
    }

    // 検索結果の更新
    function updateSearchResults() {
        const query = $('#query').val();
        const mecaer = $('#mecaer').val();
        const minPrice = $('#minPrice').val();
        const maxPrice = $('#maxPrice').val();
        const minStock = $('#minStock').val();
        const maxStock = $('#maxStock').val();

        const formData = {
            query: query,
            mecaer: mecaer,
            minPrice: minPrice,
            maxPrice: maxPrice,
            minStock: minStock,
            maxStock: maxStock
        };

        $.ajax({
            url: $('#search-form').attr('action'),
            type: $('#search-form').attr('method'),
            data: formData,
            success: function(data) {
                if (data.resultsHtml) {
                    $('#searchResults').html(data.resultsHtml);
                    $("#productTable").trigger("update"); // テーブルを更新
                } else {
                    console.error('無効なデータ形式:', data);
                    alert('無効なデータ形式が返されました。');
                }
            },
            error: function(xhr) {
                console.error('検索エラー:', xhr.responseText);
                alert('検索エラーが発生しました。');
            }
        });
    }

   
    $('#search-form').on('submit', function(event) {
        event.preventDefault();
        updateSearchResults();
    });

   
    $('#query, #mecaer, #minPrice, #maxPrice, #minStock, #maxStock').on('keypress', function(e) {
        if (e.which === 13) { 
            e.preventDefault();
            updateSearchResults();
        }
    });

    
    addDeleteEventListeners();
});
