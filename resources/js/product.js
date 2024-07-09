// resources/js/product.js
document.addEventListener('DOMContentLoaded', function () {
  var deleteForms = document.querySelectorAll('.delete-form');

  deleteForms.forEach(function (form) {
    form.addEventListener('submit', function (event) {
      event.preventDefault();
      var result = confirm('本当に削除しますか？!');

      if (result) {
        try {
          form.submit();
        } catch (error) {
          console.error('フォーム送信エラー:', error);
          alert('削除処理中にエラーが発生しました。');
        }
      }
    });
  });
});
