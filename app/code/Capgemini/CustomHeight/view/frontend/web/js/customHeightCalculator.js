define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'underscore',
    'domReady'
], function (
        $,
        modal,
        _
    ) {
    "use strict";

    var options = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        modalClass: 'custom_height_calculator',
        buttons: []
    };

    var popup = modal(options, $('#custom_height_wrapper'));
    $("#custom_height_calculator").on('click',function(){
        $("#custom_height_wrapper").modal("openModal");
    });

    $.widget('capgemini.height_calculator', {
        options: {
            desiredRooms: "#desired_rooms",
            roomName: "#room_name",
            tip: '#tip',
            tipImage: '#tip_image',
            actualHeight: '#actual_height',
            heightSelector: '#custom_height_modal',
            customHeightPdpSelector: '#custom_height',
            modalHeightCalculator: '#custom_heigth_calculator',
            proceedWithHeightBtn: '#proceed_with_height',
            proceedWithCustomTxt: 'Proceed with custom height',
            proceedWithStandardTxt: 'Proceed with standard height',
            chosenHeight: "",
            measures: {
                _1:{
                    feet:"",
                    inches:"",
                    finalValue:""
                },
                _2:{
                    feet:"",
                    inches:"",
                    finalValue:""
                },
                _3:{
                    feet:"",
                    inches:"",
                    finalValue:""
                },
            }
        },

        _create: function() {
            var self = this;
            $('#custom_height_calculator').click(function(){
                $('#desired_rooms').show();
            });
            $(this.options.heightSelector).on('change', function(el, action){
                if ($(self.options.heightSelector).attr('value') != 0) {
                    $(self.options.proceedWithHeightBtn).text(self.options.proceedWithCustomTxt);
                } else {
                    $(self.options.proceedWithHeightBtn).text(self.options.proceedWithStandardTxt);
                }
            });
            $('[name="room_list"]').on('change', function(){
                var roomConfiguration = self.options.roomConfiguration;
                var roomName = this.value;
                if (self.options.roomConfiguration.hasOwnProperty(roomName)){
                    $(self.options.desiredRooms).hide();
                    $(self.options.roomName).html('<h4>' + self.options.roomConfiguration[roomName].room_name + '</h4>');
                    var isThreeMeasures = false;

                    if(self.options.roomConfiguration[roomName].tip_image != null) {
                        $(self.options.tipImage).html(
                            '<image id="tip_image" src="' + self.options.roomConfiguration[roomName].tip_image + '" />'
                        );
                    } else {
                        $(self.options.tip).text(self.options.roomConfiguration[roomName].tip);
                    }

                    $(self.options.roomName).append('<table class="room_configuration_table"></table>');

                    if (self.options.roomConfiguration[roomName].measure_1.length > 0) {
                        var firstMeasureField  = '<tr><td>'
                            + '<div class="label">' + self.options.roomConfiguration[roomName].measure_1 + '</div>'
                            + '</td><td>'
                            + '<input type="number" id="measure_1_feet"/>ft.'
                            + '</td><td>'
                            + '<input type="number" id="measure_1_inch"/>in.'
                            + '</td></tr>';
                        $(self.options.roomName).find('.room_configuration_table').append(firstMeasureField);
                    }

                    if (self.options.roomConfiguration[roomName].measure_2.length > 0) {
                        var secondMeasureField  = '<tr><td>'
                            + '<div class="label">' +  self.options.roomConfiguration[roomName].measure_2 + '</div>'
                            + '</td><td>'
                            + '<input type="number" id="measure_2_feet"/>ft.'
                            + '</td><td>'
                            + '<input  type="number" id="measure_2_inch"/>in.'
                            + '</td></tr>';
                        $(self.options.roomName).find('.room_configuration_table').append(secondMeasureField);
                    }

                    if (self.options.roomConfiguration[roomName].measure_3.length > 0) {
                        var thirdMeasureField  = '<tr><td>'
                            + '<div class="label">' +  self.options.roomConfiguration[roomName].measure_3 + '</div>'
                            + '</td><td>'
                            + '<input type="number" id="measure_3_feet"/>ft.'
                            + '</td><td>'
                            + '<input type="number" id="measure_3_inch"/>in.'
                            + '</td></tr>';
                        $(self.options.roomName).find('.room_configuration_table').append(thirdMeasureField);
                        isThreeMeasures = true;
                    }

                    $(self.options.roomName).append(
                        '<div id="tip" class="tip">' + self.options.roomConfiguration[roomName].tip + '</div>'
                    );

                    if (isThreeMeasures) {
                        $('#measure_1_feet, #measure_1_inch, #measure_2_inch, #measure_2_feet, #measure_3_feet, #measure_3_inch').on('keyup', function (){
                            self.checkInputs(3);
                        });
                    } else {
                        $('#measure_1_feet, #measure_1_inch, #measure_2_inch, #measure_2_feet').on('keyup', function (){
                            self.checkInputs(2);
                        });
                    }
                    $(self.options.modalHeightCalculator).show();

                }
            });
            $('#proceed_with_height').click(function(){
                if (!isNaN(self.options.chosenHeight)){
                    let chosenHeight = self.options.chosenHeight;
                    let option = $(self.options.customHeightPdpSelector).find('[data-height-value="'+ chosenHeight +'"]');
                    option.prop("selected",true).trigger('change')
                    $('#custom_heigth_calculator').hide();
                    $("#custom_height_wrapper").modal("closeModal");
                }
            });

            $('#custom_height_wrapper').on('modalclosed', function() {
                $('#custom_heigth_calculator').hide();
                $('#desired_rooms').hide();
                $(self.options.actualHeight).html('&nbsp;&nbsp;&nbsp;');
                $(self.options.desiredRooms).find('input[type="radio"]').prop("checked", false);
            });

            $('#custom_height_modal').on('change', function(){
                var  selectedOption = $('#custom_height_modal option:selected');
                var selectedHeight =  selectedOption.attr('data-height-value');
                self.options.chosenHeight = selectedHeight;
            });

            $('#back_to_choose_room').click(function(){
                $(self.options.actualHeight).html('&nbsp;&nbsp;&nbsp');
                $('#custom_heigth_calculator').hide();
                $(self.options.tipImage).text('');
                $(self.options.tip).text('');
                $('#desired_rooms').show();
            });
        },
        checkInputs: function(amountOfMeasures) {
            var isInputValuesReady = true;
            self = this;
            for(var measureNumber = 1; measureNumber <= amountOfMeasures; measureNumber++ ){
                this.options.measures["_" + measureNumber].feet = parseFloat($("#measure_"+measureNumber+"_feet").val());
                this.options.measures["_" + measureNumber].inch = parseFloat($("#measure_"+measureNumber+"_inch").val());
            }
            for(var measureNumber = 1; measureNumber <= amountOfMeasures; measureNumber++ ){
                var measureValueFeet = this.options.measures["_" + measureNumber].feet;
                var measureValueInch = this.options.measures["_" + measureNumber].inch;
                if (isNaN(measureValueFeet) && isNaN(measureValueInch)) {
                    isInputValuesReady = false;
                }
            }
            if (isInputValuesReady) {
                $('#custom_height_wrapper').trigger('processStart');
                let actualHeight = this.calculateOverallHeight(amountOfMeasures)
                var maxHeightValue = this.checkMaxHeightValue();
                var minHeightValue = this.checkMinHeightValue();
                var optionHeight;
                setTimeout(function(){
                    $(self.options.actualHeight).text((actualHeight + '"'));
                    if (actualHeight > maxHeightValue) {
                        optionHeight = maxHeightValue;
                    } else {
                        optionHeight = self.findClosestOptionValue(actualHeight);
                    }

                    if (optionHeight == 0) {
                        optionHeight = minHeightValue;
                    }

                    let option = $(self.options.heightSelector).find('[data-height-value="' + optionHeight + '"]');
                    option.prop("selected",true).trigger('change');
                    self.options.chosenHeight = optionHeight;
                    $('#custom_height_wrapper').trigger('processStop');
                },500);
            }
        },

        calculateOverallHeight: function (amountOfMeasures) {
            this.convertValuesToInches(amountOfMeasures);

            let measure1 = 0;
            let measure2 = 0;
            let measure3 = 0;
            if (!isNaN(this.options.measures._1.finalValue)){
                measure1 = this.options.measures._1.finalValue;
            }
            if (!isNaN(this.options.measures._2.finalValue)){
                measure2 = this.options.measures._2.finalValue;
            }
            if (!isNaN(this.options.measures._3.finalValue)){
                measure3 = this.options.measures._3.finalValue;
            }
            let oaHeight = 0;
            let minHeight = 0;
            let maxHeight = 0;

            if (!_.isEmpty(this.options.productConfig.oaHeight)){
                oaHeight = this.options.productConfig.oaHeight;
            }
            if (!_.isEmpty(this.options.productConfig.minHeight)){
                minHeight = this.options.productConfig.minHeight;
            }
            if (!_.isEmpty(this.options.productConfig.maxHeight)){
                maxHeight = this.options.productConfig.maxHeight;
            }

            let idealHeight = measure1 - measure2 - measure3;
            idealHeight = Math.floor(idealHeight * 2) / 2;

            var actualHeight = idealHeight;

            return actualHeight;
        },

        checkMaxHeightValue: function () {
            var temp=0;
            var currentValue, maxValue;

            $('#custom_height_modal option').each(function() {
                currentValue = $(this).attr('data-height-value');
                temp = Math.max(temp, currentValue);
                maxValue = temp;
            });

            return maxValue;
        },

        checkMinHeightValue: function () {
            var temp=100000;
            var currentValue, minValue;

            $('#custom_height_modal option').each(function() {
                currentValue = $(this).attr('data-height-value');
                temp = Math.min(temp, currentValue);
                minValue = temp;
            });

            return minValue;
        },

        /**
         * Find the closest select option value to the given value
         *
         * Note, function will only return a value that is equal or the next
         * lowest value.
         *
         * @param value
         * @returns {number}
         */
        findClosestOptionValue: function (value) {
            var closestHeight = 0;
            $(self.options.heightSelector + ' option').each(function(index, opt) {
                var optValue = $(opt).attr('data-height-value');
                if (closestHeight == 0 && optValue <= value) {
                    closestHeight = optValue;
                }
                $(opt).prop("selected", false);
            });

            return closestHeight;
        },

        convertValuesToInches: function(amountOfMeasures)
        {
            for(var measureNumber = 1; measureNumber <= amountOfMeasures; measureNumber++ ){
                let feet = 0;
                let inches = 0;
                if (!isNaN(this.options.measures["_"+ measureNumber].feet)){
                    feet = this.options.measures["_" + measureNumber].feet;
                }
                if (!isNaN(this.options.measures["_"+ measureNumber].inch)){
                    inches = this.options.measures["_" + measureNumber].inch;
                }
                this.options.measures["_" + measureNumber].finalValue = (feet * 12) + inches;
            }
        }
    });

    return $.capgemini.height_calculator;
});
