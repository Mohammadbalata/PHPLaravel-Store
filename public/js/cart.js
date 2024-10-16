(function ($) {
    $(".item-quantity").on("change", function () {
        $.ajax({
            url: "/cart/" + $(this).data("id"),
            method: "PUT",
            data: {
                quantity: $(this).val(),
                _token: csrf_token,
            },
        });
    });

    $(".remove-item").on("click", function () {
        let id = $(this).data("id");
        $.ajax({
            url: "/cart/" + id,
            method: "DELETE",
            data: {
                _token: csrf_token,
            },
            success: (response) => {
                $(`#row${id}`).remove();
                $(`#row${id}`).remove();
            },
        });
    });

    $(".add-to-cart").on("click", function (e) {
        $.ajax({
            url: "/cart",
            method: "post",
            data: {
                product_id: $(this).data("id"),
                quantity: $(this).data("quantity"),
                _token: csrf_token,
            },
            success: (response) => {
                alert("product added");
            },
        });
    });
})(jQuery);
