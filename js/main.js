
// Stuff to change
// $("#background-gif").attr("rel:animated_src") -> gif link
// $("#gif-name").attr("href") -> comments link
// $("#gif-name").text() -> gif title
// $("#gif").attr("src") -> gif link
//
// image.name = gif link
// image.src = gif link

function setImg(i){

    $("#background-gif").attr("src", gifs[i]);
    $("#gif-name").attr("href", comments[i]); // -> comments link
    $("#gif-name").text(titles[i]); // -> gif title
    $("#gif").attr("src", gifs[i]); // -> gif link

    var gifWidth;
    var gifHeight;

    var image = new Image();
    image.name = gifs[i];
    image.onload = function(){
        gifWidth = this.width;
        gifHeight = this.height;
        gifScale = gifWidth/gifHeight;

        containerWidth = $("#gifContainer").width();
        containerHeight = $(window).height();
        containerScale = containerWidth/containerHeight;

        if (containerScale > gifScale){
            $("#gif").height(containerHeight); // expand height

            $("#gif").load(function(){
                $("#prev").html('Prev');
                $("#next").html('Next');
            });

            $("#background-gif").width("100%");
        }
        else {
            $("#gif").width("100%"); // expand width

            $("#gifContainer").css({
                "padding-left": "0",
                "padding-right": "0"
            });

            $("#gifContainer").css("padding-top", "150px");
            $("#gif").load(function(){
                $("#gifContainer").css("padding-top", ($(window).height() - $(this).height())/2);
                $("#prev").html('Prev');
                $("#next").html('Next');
            });

            $("#background-gif").height("120%");
        }

        $("#background-gif").css({
            "position": "fixed",
            "z-index": "-2",
            "opacity": "0.8"
        });

        return true;
    };
    image.src = gifs[i];
};


// Disable prev button if on first
function setButtons(){
    if (index <= 0)
        $("#prev").addClass("disabled");
    else
        $("#prev").removeClass("disabled");
    if (index >= gifs.length-1)
        $("#next").addClass("disabled");
    else
        $("#next").removeClass("disabled");
};


$("#next").on("click",function(){
    index++;

    $(this).html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Next');

    setImg(index);
    setButtons();
});

$("#prev").on("click",function(){
    if (index > 0){
        index--;

        $(this).html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Prev');

        setImg(index);
    }
    setButtons();
});


setImg(index);
setButtons();





