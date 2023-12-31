/* global cf7dfv_ajax */

(function ($) {
    'use strict';

    $(document).ready(function () {

        var name = $("#cf7dfv_item-name");
        var btn_add_item = $('#cf7dfv_btn_add_item');
        var item_list = $("#cf7dfv_item-list");
        var items = [];

        btn_add_item.click(function () {
            let itemName = name.val();
            if (itemName.trim() !== "") {
                if (!items.includes(itemName)) {
                    items.push(itemName);
                    renderItems();
                    saveData();
                    name.val('');
                } else {
                    alert(cf7dfv_ajax.already_exists);
                }
            }
        });

        function renderItems() {
            item_list.empty();

            items.forEach(function (item, index) {
                var listItem = $("<li>")
                        .append(
                                $("<span>")
                                .text(item)
                                )
                        .append(
                                $("<button>")
                                .text(cf7dfv_ajax.edit)
                                .click(function () {
                                    editItem(index);
                                })
                                )
                        .append(
                                $("<button>")
                                .text(cf7dfv_ajax.delete)
                                .click(function () {
                                    deleteItem(index);
                                })
                                );

                item_list.append(listItem);
            });
        }

        function editItem(index) {
            var newItemName = prompt(cf7dfv_ajax.edit_item_name, items[index]);
            if (newItemName !== null) {
                items[index] = newItemName;
                renderItems();
                saveData();
            }
        }

        function deleteItem(index) {
            items.splice(index, 1);
            renderItems();
            saveData();
        }

        function saveData() {
            $.ajax({
                type: "POST",
                url: cf7dfv_ajax.url,
                data: {
                    action: "save_data",
                    items: JSON.stringify(items)
                },
                success: function (response) {
                    console.log(response);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    console.error(error);
                }
            });
        }

        function getData() {
            return new Promise(function (resolve, reject) {
                $.ajax({
                    type: "GET",
                    url: cf7dfv_ajax.url,
                    data: {action: "get_data"},
                    success: function (response) {
                        try {
                            items = response.data;

                            if (!Array.isArray(items)) {
                                console.warn("Data is not an array. Resetting items to an empty array.");
                                items = [];
                            }

                            resolve(items);
                        } catch (e) {
                            console.error("Error parsing data");
                            reject(e);
                        }
                    },
                    error: function (error) {
                        console.error(error);
                        reject(error);
                    }
                });
            });
        }

        getData()
                .then(function (data) {
                    items = data;
                    renderItems();
                })
                .catch(function (error) {
                    console.error(error);
                });
    });
})(jQuery);
