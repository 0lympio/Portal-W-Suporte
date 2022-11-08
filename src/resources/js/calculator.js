$(document).ready(function () {
    $(function () {
        $(".button").click(function () {
            var num = $(this).html();
            checkButtonValue(num);
        });

        $("#calculator").draggable();
    });

    //A function that checks the value of the button
    function checkButtonValue(button) {
        if (/[0-9]/.test(button)) {
            addTextToScreen(button);
        } else if (/[\u00F7\u00D7+-.]/.test(button)) {
            var currentValue = $("#value").html();
            if (currentValue !== "0") {
                if (!wasCalcButton()) {
                    addTextToScreen(button);
                }
            }
        } else if (/C/.test(button)) {
            $("#value").html("0");
        } else if (/%/.test(button)) {
            runCalc();
            var percentNum = parseFloat($("#value").html()) / 100;
            $("#value").html(percentNum);
        } else if (/=/.test(button)) {
            runCalc();
        } else if (/\u00B1/.test(button)) {
            runCalc();
            var negativeNum = $("#value").html() * -1;
            $("#value").html(negativeNum);
        }
    }

    //A function that checks if there is text in the screen.  If there is then return true.
    function checkScreen() {
        return $("#value").text().length() > 0;
    }

    //A function that adds text to the screen
    function addTextToScreen(num) {
        if ($("#value").html() !== "0") {
            var val = $("#value").html();
            $("#value").html(val + num);
        } else {
            $("#value").html(num);
        }
    }

    //Checks to see if previous button was an operator, returns true or false
    function wasCalcButton() {
        var currentValue = $("#value").html();
        var valueLength = currentValue.length;
        var finalElement = currentValue.substring(valueLength - 1);
        return /[\u00F7\u00D7+-]/.test(finalElement);
    }

    //A function that reads in the text and makes it into an array
    function makeValueIntoArray() {
        var valueString = $("#value").html();
        var valueArray = [];
        var elementHolder = "";
        for (var i = 0; i < valueString.length; i++) {
            if (/[0-9]/.test(valueString[i])) {
                elementHolder += valueString[i];
            } else {
                if (elementHolder !== "") {
                    valueArray.push(elementHolder);
                }
                valueArray.push(valueString[i]);
                elementHolder = "";
            }
        }
        valueArray.push(elementHolder);

        return valueArray;
    }

    //A function that runs the calculation of all the elements
    function runCalc() {
        var valueArray = makeValueIntoArray();
        if (valueArray[0] === "-") {
            valueArray[1] = valueArray[0] + valueArray[1];
            valueArray.shift();
        }

        valueArray = decimalPoints(valueArray);
        valueArray = division(valueArray);
        valueArray = multiplication(valueArray);
        valueArray = addition(valueArray);
        valueArray = subtraction(valueArray);

        let decimals = 1000000  // 6 decimal places
        valueArray[0] = parseInt(valueArray[0] * decimals) / decimals

        $("#value").html(valueArray[0]);
    }

    //Combine decimal points
    function decimalPoints(array) {
        while (array.indexOf(".") > 0) {
            var location = array.indexOf(".");

            array[location] = array[location - 1] + "." + array[location + 1];
            array[location - 1] = "null";
            array[location + 1] = "null";
            array = array.filter(function (element) {
                return element !== "null";
            });
        }
        return array;
    }

    //Division Function
    function division(array) {
        while (array.indexOf("\u00F7") > 0) {
            var location = array.indexOf("\u00F7");

            array[location] =
                parseFloat(array[location - 1]) /
                parseFloat(array[location + 1]);

            array[location - 1] = "null";
            array[location + 1] = "null";
            array = array.filter(function (element) {
                return element !== "null";
            });
        }
        return array;
    }

    //Multiplication Function
    function multiplication(array) {
        while (array.indexOf("\u00D7") > 0) {
            var location = array.indexOf("\u00D7");

            array[location] =
                parseFloat(array[location - 1]) *
                parseFloat(array[location + 1]);
            array[location - 1] = "null";
            array[location + 1] = "null";
            array = array.filter(function (element) {
                return element !== "null";
            });
        }
        return array;
    }

    //Addition Function
    function addition(array) {
        while (array.indexOf("+") > 0) {
            var location = array.indexOf("+");

            array[location] =
                parseFloat(array[location - 1]) +
                parseFloat(array[location + 1]);
            array[location - 1] = "null";
            array[location + 1] = "null";
            array = array.filter(function (element) {
                return element !== "null";
            });
        }
        return array;
    }

    function subtraction(array) {
        while (array.indexOf("-") > 0) {
            var location = array.indexOf("-");

            array[location] =
                parseFloat(array[location - 1]) -
                parseFloat(array[location + 1]);
            array[location - 1] = "null";
            array[location + 1] = "null";
            array = array.filter(function (element) {
                return element !== "null";
            });
        }
        return array;
    }
});
