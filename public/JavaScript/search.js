$(document).ready(function() {
    // 商品削除のイベントリスナーを追加
    function addDeleteEventListeners() {
        $(document).on('submit', '.delete-form', function(event) {
            event.preventDefault();

            if (confirm('削除しますか？')) {
                const form = $(this);
                const productId = form.data('id');

                $.ajax({
                    url: form.attr('action'),
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(data) {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            $(`#product-${productId}`).remove();
                            alert(data.success);
                        }
                    },
                    error: function(xhr) {
                        console.error('削除エラー:', xhr.responseText);
                        alert('削除エラーが発生しました。');
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

    // 検索ボタンにクリックイベントを追加
    $('#searchButton').on('click', function() {
        updateSearchResults();
    });

    // 入力フィールドでEnterキーが押されたときに検索をトリガー
    $('#query, #mecaer, #minPrice, #maxPrice, #minStock, #maxStock').on('keypress', function(e) {
        if (e.which === 13) { // Enterキーが押されたとき
            e.preventDefault();
            updateSearchResults();
        }
    });

    // 検索フォームの送信イベントをハンドリング
    $('#search-form').on('submit', function(event) {
        event.preventDefault();
        updateSearchResults();
    });

    // ページ読み込み時に削除イベントリスナーを追加
    addDeleteEventListeners();
});
