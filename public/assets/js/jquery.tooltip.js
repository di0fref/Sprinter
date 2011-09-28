(function($) {
    $.fn.tooltip = function(options) {

        var defaults = {
            tooltip: "",
            width: "",
            height: "",
            position: "mouse", //top, right, bottom, left
            offsetX: "10",
            offsetY: "10",
            attr: "",
            cssclass: "",
            parentID: ""
        };

        var options = $.extend({}, defaults, options);

        this.each(function() {
            obj = $(this);
            var left, top;
            if (options.attr == "") {
                tooltip = $(options.tooltip);
            } else {
            var parentID;
                
                if (obj.attr("id") == "") {
                    parentID = options.parentID;
                } else {
                    parentID = obj.attr("id");
                }
                
                $("body").append('<div id="tt_' + parentID + '"></div>');
                $("#tt_" + parentID).html(obj.attr(options.attr)).addClass(options.cssclass);
                options.tooltip = $("#tt_" + parentID);
                tooltip = $(options.tooltip);
            }

            tooltip.hide();

            offset = $(this).offset();


            switch (options.position) {
                case "top":
                    top = (parseInt($(this).offset().top) - (parseInt(tooltip.height()))) - parseInt(options.offsetY);
                    left = parseInt($(this).offset().left) + parseInt(options.offsetX);

                    break;
                case "left":
                    left = (parseInt($(this).offset().left) - (parseInt(tooltip.width()))) - parseInt(options.offsetX);
                    top = parseInt($(this).offset().top) + parseInt(options.offsetY);

                    break;
                case "right":
                    left = (parseInt($(this).offset().left) + (parseInt(obj.width()))) + parseInt(options.offsetX);
                    top = parseInt($(this).offset().top) + parseInt(options.offsetY);
                    break;
                case "bottom":
                    top = (parseInt($(this).offset().top) + (parseInt(obj.height()))) + parseInt(options.offsetY);
                    left = parseInt($(this).offset().left) + parseInt(options.offsetX);

                    break;
                default:
                    obj.bind("mouseenter", function() {
                        $(options.tooltip).fadeIn("fast");
                    });

                    obj.bind("mousemove", function(e) {
                        $(options.tooltip).css({
                            'top': e.pageY + parseInt(options.offsetY),
                            'left': e.pageX + parseInt(options.offsetX),
                            'position': 'absolute',
                            'width': options.width + "px",
                            'height': options.height + "px"
                        });
                    });
            }

            if (options.position != "mouse") {
                obj.bind("mouseenter", function() {
                    $(options.tooltip).fadeIn("fast");
                });

                $(options.tooltip).css({
                    'position': 'absolute',
                    'top': top,
                    'left': left,
                    'width': options.width + "px",
                    'height': options.height + "px"
                });
            }

            obj.mouseleave(function() {
                $(options.tooltip).fadeOut("fast");
                if (options.attr != "") {
                    // $(options.tooltip).remove();
                }
            });

        });

        return this;
    };
})(jQuery);