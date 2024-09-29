$(document).ready(function() {
    // テーブルソートの初期化
    $("#productTable").tablesorter({
        theme: 'default',
        headers: {
            4: { sorter: false }  
        }
    });

    // ローディングインジケーターの表示
    function showLoading() {
        $('#loading-indicator').show();
    }

    // ローディングインジケーターの非表示
    function hideLoading() {
        $('#loading-indicator').hide();
    }

    // 検索結果を更新する関数
    function updateSearchResults() {
        var formData = $('#search-form').serialize();  

        $.ajax({
            url: $('#search-form').attr('action'),  
            method: $('#search-form').attr('method'),
            type: 'GET', 
            data: formData,  
            beforeSend: function() {
                showLoading();  
            },
            success: function(response) {
                
                $('#searchResults').html(response.html);  
                $("#productTable").trigger("update");  
                hideLoading();  
            },
            error: function(xhr, status, error) {
                console.error('検索エラー:', xhr.responseText);
                alert("検索中にエラーが発生しました。");
                hideLoading();
            }
        });
    }

    
    $('#search-form').on('submit', function(event) {
        event.preventDefault();  
        updateSearchResults();  
    });

    // Enterキーで検索を実行
    $('#query, #manufacturer, #minPrice, #maxPrice, #minStock, #maxStock').on('keypress', function(e) {
        if (e.which === 13) {  
            e.preventDefault();  
            updateSearchResults();  
        }
    });

    // 削除イベントの設定
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
                        $(`#product-${productId}`).fadeOut(300, function() {
                            $(this).remove();  
                            $("#productTable").trigger("update");  
                        });
                        alert(data.success || '削除に成功しました。');
                    },
                    error: function(xhr) {
                        console.error('削除エラー:', xhr.responseText);
                        alert('削除中にエラーが発生しました。');
                    }
                });
            }
        });
    }

    addDeleteEventListeners();  
});
