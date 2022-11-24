CKEDITOR.replace('editor', {
    extraPlugins: 'wordcount',
    wordcount: {
        showWordCount: false,
        showCharCount: true,
       // maxWordCount: 4,
        maxCharCount: 160,
        paragraphsCountGreaterThanMaxLengthEvent: function(currentLength, maxLength) {
            $("#informationparagraphs").css("background-color", "crimson").css("color", "white").text(
                currentLength + "/" + maxLength + " - paragraphs").show();
        },
        // wordCountGreaterThanMaxLengthEvent: function(currentLength, maxLength) {
        //     $("#informationword").css("background-color", "crimson").css("color", "white").text(
        //         currentLength + "/" + maxLength + " - word").show();
        // },
        charCountGreaterThanMaxLengthEvent: function(currentLength, maxLength) {
            $("#informationchar").css("background-color", "crimson").css("color", "white").text(
                currentLength + "/" + maxLength + " - char").show();
        },
        charCountLessThanMaxLengthEvent: function(currentLength, maxLength) {
            $("#informationchar").css("background-color", "white").css("color", "black").hide();
        },
        paragraphsCountLessThanMaxLengthEvent: function(currentLength, maxLength) {
            $("#informationparagraphs").css("background-color", "white").css("color", "black").hide();
        },
        // wordCountLessThanMaxLengthEvent: function(currentLength, maxLength) {
        //     $("#informationword").css("background-color", "white").css("color", "black").hide();
        // }
    }
});
