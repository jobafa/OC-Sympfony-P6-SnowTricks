$(function() {

    /* ****** LoadMore Tricks and LoadLess trick buttons ***** */

    var limit = 5;
    var commentsPerPage = 5;

    var tricks = $("div.tricks-div");
    $("#arrowUp").hide();
    $("#LessTricks").hide();
    if (tricks.length <= limit) {
        $("#MoreTricks").hide();
    }

    for (var i = limit; i <= tricks.length - 1; i++) {
        tricks[i].remove();
    }

    $("#MoreTricks").on("click", function(e) {
        e.preventDefault();
        limit += 5;
        for (var i = 0; i <= limit - 1; i++) {
            $("#tricks").append(tricks[i]);
        }
        if (tricks.length <= limit) {
            $("#LessTricks").show();
            $("#MoreTricks").hide();
        }
        if (limit >= 8) {
            $("#arrowUp").show();
        }
    });

    $("#LessTricks").on("click", function(e) {
        e.preventDefault();
        limit = 5;
        for (var i = limit; i <= tricks.length - 1; i++) {
            tricks[i].remove();
        }
        $("#LessTricks").hide();
        $("#MoreTricks").show();
        $("#arrowUp").hide();

    });


 
    /* ******** trick page ****** */

    $("#trickPage #trickMedia button").click(function(e) {
        $("#trickPage #trickMedia .media-slider").css("display", "block");
        $(this).css("display", "none");
    });

    $(".trick-media").click(function() {
        var trickId = $(this).attr("id");
        var carouselId = "carousel" + trickId;
        $(".carousel-item[id =" + carouselId + "]").addClass("active");
    })

    $("#modalGallery").on("hide.bs.modal", function(e) {
        $(".carousel-item").removeClass("active");
    })

    /* ******** new/edit trick page ****** */
    // Trick images upload and videos collection management

//ADDED FOR TESTING
    
/* jQuery(document).ready(function() {
    var $wrapper = $('.trick-image-wrapper');
    $wrapper.on('click', '.js-remove-image', function(e) {
        e.preventDefault();
        $(this).closest('.js-trick-image-item')
            .fadeOut()
            .remove();
    });
}); */
// END TESTING

    $(document).on("change", ".custom-file-input", function() {
        let fileName = $(this).val().replace(/\\/g, "/").replace(/.*\//, "");
        $(this).parent(".custom-file").find(".custom-file-label").text(fileName);
    });

    /* function handleDeleteButtons() {
        $("button[data-action='delete']").click(function () {
            const target = this.dataset.target;
            $(target).remove();
            updateCounterImage();
            updateCounterVideo();
            displayCounter();
        });
    } */

    function handleDeleteButtons() {
        $("button[data-action='delete']").click(function() {
            /* var target = $(this).attr("data-target");
            $(target).parent().remove(); */
            const target = this.dataset.target;
            $(target).remove();
            updateCounterImage();
            updateCounterVideo();
        })
    }

   /*  function updateCounterImage() {
        const count = +$("#trick_images div.form-group").length;
        $("#image-counter").val(count);
    } */

    function updateCounterImage() {
        var count = +$("#image-fields-list").length;
        $("#image-counter").val(count);
    }

    function updateCounterVideo() {
        var count = +$("#video-fields-list").children().length;
        $("#video-counter").val(count);
    }

    $(".add-another-collection-widget").click(function(e) {
        var list = $($(this).attr("data-list-selector"));
        // Try to find the counter of the list or use the length of the list
        var counter = list.data("widget-counter") || list.children().length;

        // grab the prototype template
        var newWidget = list.attr("data-prototype");
        // replace the "__name__" used in the id and name of the prototype
        // with a number that"s unique to your emails
        // end name attribute looks like name="contact[emails][2]"
        newWidget = newWidget.replace(/__name__/g, counter);
        // Increase the counter
        counter++;
        // And store it, the length cannot be used if deleting widgets is allowed
        list.data("widget-counter", counter);

        // create a new list element and add it to the list
        var newElem = $(list.attr("data-widget-tags")).html(newWidget);
        newElem.appendTo(list);
        handleDeleteButtons();
        updateCounterImage();
        updateCounterVideo();
    });

    $(".edit-mainImg").click(function(e) {
        $(".mainImg-input .custom-file").css("display", "block");
    })

    $(".delete-mainImg").click(function(e) {
        $("#trickMainImg").css("background", "none").css("background-color", "grey");
        $(".mainImg-input").css("display", "block");
    })

    $(".edit-media-button").click(function(e) {
        $(this).parent().parent().find(".edit-media-input").css("display", "block");
    })

    $(".delete-media-button").click(function(e) {
        $(this).parent().parent().remove();
    })

    $("#editPage #trickMedia button").click(function(e) {
        $("#editPage #trickMedia .media-slider").css("display", "block");
        $(this).css("display", "none");
        $("#newMedia").css("display", "block !important");
    })


    /* ********** Passing user infos to modal ********* */

    $("#userModal").on("show.bs.modal", function(e) {
        $(this).find("#userModalName").text($(e.relatedTarget).data("name"));
        $(this).find("#userModalAvatar").attr("src", $(e.relatedTarget).data("avatar"));
        $(this).find("#userModalEmail").text($(e.relatedTarget).data("description"));
    });

    /* ********** Passing trick infos to modal ********* */

    $("#deleteTrickModal").on("show.bs.modal", function(e) {
        $(this).find("#trick_deletion").attr("action", $(e.relatedTarget).data("action"));
        $(this).find("#csrf_deletion").attr("value", $(e.relatedTarget).data("token"));
        $(this).find(".modal-title").text("Trick deletion : " + $(e.relatedTarget).data("name"));
    });

    /* ********** Passing trick infos to main image deletion modal ********* */

    $("#deletedefaultimageModal").on("show.bs.modal", function(e) {
        $(this).find("#defaultImage_delete").attr("action", $(e.relatedTarget).data("action"));
        $(this).find("#csrf_deletion").attr("value", $(e.relatedTarget).data("token"));
    });

    /* ********** Passing comment infos to modal ********* */

    $("#deleteCommentModal").on("show.bs.modal", function(e) {
        $(this).find("#comment_deletion").attr("action", $(e.relatedTarget).data("action"));
        $(this).find("#csrf_deletion").attr("value", $(e.relatedTarget).data("token"));
    });

    /* ********** Passing group infos to modal ********* */

    $("#deleteGroupModal").on("show.bs.modal", function(e) {
        $(this).find("#group_deletion").attr("action", $(e.relatedTarget).data("action"));
        $(this).find("#csrf_deletion").attr("value", $(e.relatedTarget).data("token"));
    });

    /* ******** user profile page ****** */

    $("#editAvatarBtn").click(function(e) {
        $(".avatar-input .custom-file").css("display", "block");
        $(this).css("display", "none");
    })


}); 

//TESTING REMOVING IMAGE FROM COLLECTION TYPE
jQuery(document).ready(function() {
    var $wrapper = $('.js-genus-scientist-wrapper');
    $wrapper.on('click', '.js-remove-scientist', function(e) {
        e.preventDefault();
        $(this).closest('.js-genus-scientist-item')
            .remove();
    });
});