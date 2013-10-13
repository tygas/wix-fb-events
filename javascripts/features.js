$('.accordion').Accordion(
    {
        triggerClass: "features",
        contentClass: "feature"
    }
);

$('.background-picker').ColorPicker();
$('.list-picker').ColorPicker();


$(document).on("colorChanged", function (event, data) {
        // data.type now has the id of the color-picker (in case you have more than one
        // data.selected_color has the selected color ('#ffffff' for example)

        $('#'+data.type+'-color').val(data.selected_color);

    }
)
;