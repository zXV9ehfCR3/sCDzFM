var x = 0;
var coins = false;
var kits = false;
$(document).ready(function () {
    $("#coins").click(function () {
        switch_before(0, 225, "coins");
    });
    $("#kits").click(function () {
        switch_before(0, 325, "kits");
    });
    $("#wins").click(function () {
        switch_before(0, 225, "wins");
    });
    $("#kills").click(function () {
        switch_before(0, 150, "kills");
    });
    $("#defaults").click(function () {
        switch_before(0, 150, "defaults");
    });
});

function switch_before(section, height_val, element) {
    setTimeout(function () {
        if (section == 0) {
            the_height = height_val + "px";
            if ($("#" + element).attr('data-elem') == "+") {
                x = 0;
                $("#" + element).attr('data-elem', '-');
                $("." + element).css('display', 'block');
            } else {
                $("#" + element).attr('data-elem', '+');
                $("." + element).css('color', 'transparent');
                x = height_val;
            }

            switch_before(1, height_val, element);
        } else if (section == 1) {
            if ($("#" + element).attr('data-elem') == '-') {
                x += 25;
                $("." + element).css('height', x);
                if (x + "px" == the_height) {
                    $("." + element).css('color', 'black');
                    return;
                }
            } else {
                x -= 25;
                $("." + element).css('height', x);
                if (x + "px" == "0px") {
                    $("." + element).css('display', 'none');
                    return;
                }
            }
            switch_before(1, height_val, element);
        }
    }, 5);
}