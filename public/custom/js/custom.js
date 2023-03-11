"use strict";

var totalMinutes = 0;

$(function () {
    commonLoader();

    // const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
    // ];

    // Prevent Modal Close On Click Outside
    new bootstrap.Modal(document.getElementById("commonModal"), {
        backdrop: "static",
        keyboard: false,
    });

    const getTimeDifference = (msec) => {
        let totalMinutes = Math.floor(msec / 60000); // Total Difference in Minutes
        let hrs = String(Math.floor(totalMinutes / 60)); // Total Difference in Hours
        hrs = hrs.padStart(2, "0");
        let mins = String(totalMinutes % 60); // Display Minutes
        mins = mins.padStart(2, "0");
        return `${hrs}:${mins}`;
    };

    const getHrMins = (time) => {
        let startTime_hrsMins = time.split(":");
        let msec = getMilliSeconds(startTime_hrsMins[0], startTime_hrsMins[1]);
        return getTimeDifference(msec);
    };

    const getTimeDifferences = (date, startTime, endTime) => {
        var date1 = new Date(date + " " + startTime).getTime();
        var date2 = new Date(date + " " + endTime).getTime();
        var msec = date2 - date1; // Total Difference in Seconds
        var mins = Math.floor(msec / 60000); // Total Difference in Minutes
        var hrs = Math.floor(mins / 60); // Total Difference in Hours
        mins = mins % 60; // Display Minutes
        return [hrs, mins];
    };

    // convert hours and minutes into micro-seconds
    const getMilliSeconds = (h, m) => (h * 60 * 60 + m * 60) * 1000;

    $(document).on("keyup change", "#start_time, #end_time", function () {
        let date = $("#date").val();
        let start_time = $("#start_time").val();
        let end_time = $("#end_time").val();

        console.log(date, start_time, end_time);

        if (date !== "" && start_time !== "" && end_time !== "") {
            let hrsMins = getTimeDifferences(date, start_time, end_time);
            //console.log(hrsMins);
            $("#hours").val(hrsMins[0]);
            $("#minutes").val(hrsMins[1]);
        }
    });

    //Prevent Modal Close On Esc Button
    $(document).on("keyup", "#hours, #minutes", function () {
        //let end_time = '';
        //let date = $('#date').val();
        let hours = $("#hours").val();
        hours = hours.padStart(2, "0");
        let minutes = $("#minutes").val();
        minutes = minutes.padStart(2, "0");
        let start_time = $("#start_time").val();
        if (
            (hours !== "" || hours > 0) &&
            (minutes !== "" || minutes > 0) &&
            start_time !== ""
        ) {
            let startTime_hrsMins = start_time.split(":");
            let startTime_hrs = startTime_hrsMins[0];
            let startTime_mins = startTime_hrsMins[1];
            startTime_hrs = startTime_hrs.padStart(2, "0");
            startTime_mins = startTime_mins.padStart(2, "0");

            let totalHrs = parseInt(startTime_hrs) + parseInt(hours);
            let totalMins = parseInt(startTime_mins) + parseInt(minutes);
            let msec = getMilliSeconds(totalHrs, totalMins);

            let newEndTime = getTimeDifference(msec);
            $("#end_time").val(newEndTime);
        }
    });
});

function show_toastr(title, message, type) {
    var o, i;
    var icon = "";
    var cls = "";

    if (type == "success") {
        icon = "fas fa-check-circle";
        cls = "primary";
    } else {
        icon = "fas fa-times-circle";
        cls = "danger";
    }

    $.notify(
        { icon: icon, title: " " + title, message: message, url: "" },
        {
            element: "body",
            type: cls,
            allow_dismiss: !0,
            placement: {
                from: "top",
                align: toster_pos,
            },
            offset: { x: 15, y: 15 },
            spacing: 10,
            z_index: 1080,
            delay: 2500,
            timer: 2000,
            url_target: "_blank",
            mouse_over: !1,
            animate: { enter: o, exit: i },
            template:
                '<div class="toast text-white bg-' +
                cls +
                ' fade show" role="alert" aria-live="assertive" aria-atomic="true">' +
                '<div class="d-flex">' +
                '<div class="toast-body"> ' +
                message +
                " </div>" +
                '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
                "</div>" +
                "</div>",
        }
    );
}

$(document).on("click", ".bs-pass-para", function () {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
    });
    swalWithBootstrapButtons
        .fire({
            title: $(this).data("confirm"),
            text: $(this).data("text"),
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            reverseButtons: false,
        })
        .then((result) => {
            if (result.isConfirmed) {
                document.getElementById($(this).data("confirm-yes")).submit();
            } else if (result.dismiss === Swal.DismissReason.cancel) {
            }
        });
});

// Start : Remove Timesheet single log
$(document).on("click", ".timesheet-remove-log", function () {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
    });
    swalWithBootstrapButtons
        .fire({
            title: $(this).data("confirm"),
            text: $(this).data("text"),
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            reverseButtons: false,
        })
        .then((result) => {
            if (result.isConfirmed) {
                removeEntry($(this).data("id"), $(this).data("logged-time"));

                if ($("#entries tr").length < 1) {
                    $("#showEntries").hide();
                }
            } else if (result.dismiss === Swal.DismissReason.cancel) {
            }
        });
});

const removeEntry = (button_id, loggedTime) => {
    $("#row_" + button_id).remove();
    getHrsMinutes(totalMinutes - loggedTime);
};

// Update total Time
const getHrsMinutes = (msec) => {
    totalMinutes += Math.floor(msec / 60000); // Total Difference in Minutes
    let hrs = Math.floor(totalMinutes / 60); // Total Difference in Hours
    let mins = totalMinutes % 60; // Display Minutes
    $("#totalTime").html(`${hrs}h ${mins}m`);
};

// End : Remove Timesheet single log

// $('#myModal').modal({
//     backdrop: 'static'
// });

// Start : Remove Timesheet all logs

$(document).on("click", ".timesheet-remove-all-logs, btn-close", function () {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
    });
    swalWithBootstrapButtons
        .fire({
            title: $(this).data("confirm"),
            text: $(this).data("text"),
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            reverseButtons: false,
        })
        .then((result) => {
            if (result.isConfirmed) {
                $("#entries tr").remove();
                totalMinutes = 0; // Total Difference in Minutes
                $("#totalTime").html("0h 0m");
                $("#commonModal").modal("hide");
            } else if (result.dismiss === Swal.DismissReason.cancel) {
            }
        });
});

// End : Remove Timesheet all logs

$(document).on("click", ".bs-pass-fn-call", function () {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
    });
    swalWithBootstrapButtons
        .fire({
            title: $(this).data("confirm"),
            text: $(this).data("text"),
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            reverseButtons: false,
        })
        .then((result) => {
            if (result.isConfirmed) {
                removeImage($(this).data("id"));
            } else if (result.dismiss === Swal.DismissReason.cancel) {
            }
        });
});

$(document).on(
    "click",
    'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]',
    function () {
        var title = $(this).data("title");
        var size = $(this).data("size") == "" ? "md" : $(this).data("size");
        var url = $(this).data("url");
        $("#commonModal .modal-title").html(title);
        $("#commonModal .modal-dialog").addClass("modal-" + size);

        $.ajax({
            url: url,
            success: function (data) {
                $("#commonModal .modal-inner-data").html(data);
                $("#commonModal").modal("show");

                taskCheckbox();
                commonLoader();
            },
            error: function (data) {
                data = data.responseJSON;
                show_toastr("Error", data.error, "error");
            },
        });
    }
);

$(document).on(
    "click",
    'a[data-ajax-popup-over="true"], button[data-ajax-popup-over="true"], div[data-ajax-popup-over="true"]',
    function () {
        var validate = $(this).attr("data-validate");
        var id = "";
        if (validate) {
            id = $(validate).val();
        }

        var title = $(this).data("title");
        var size = $(this).data("size") == "" ? "md" : $(this).data("size");
        var url = $(this).data("url");

        $("#commonModalOver .modal-title").html(title);
        $("#commonModalOver .modal-dialog").addClass("modal-" + size);

        $.ajax({
            url: url + "?id=" + id,
            success: function (data) {
                $("#commonModalOver .modal-inner-data").html(data);
                $("#commonModalOver").modal("show");
                taskCheckbox();
            },
            error: function (data) {
                data = data.responseJSON;
                show_toastr("Error", data.error, "error");
            },
        });
    }
);

function arrayToJson(form) {
    var data = $(form).serializeArray();
    var indexed_array = {};

    $.map(data, function (n, i) {
        indexed_array[n["name"]] = n["value"];
    });

    return indexed_array;
}

$(document).on("submit", "#commonModalOver form", function (e) {
    e.preventDefault();
    var data = arrayToJson($(this));
    data.ajax = true;

    var url = $(this).attr("action");
    $.ajax({
        url: url,
        data: data,
        type: "POST",
        success: function (data) {
            show_toastr("Success", data.success, "success");
            $(data.target).append(
                '<option value="' +
                    data.record.id +
                    '">' +
                    data.record.name +
                    "</option>"
            );
            $(data.target).val(data.record.id);
            $(data.target).trigger("change");
            $("#commonModalOver").modal("hide");
            commonLoader();
        },
        error: function (data) {
            data = data.responseJSON;
            show_toastr("Error", data.error, "error");
        },
    });
});

function taskCheckbox() {
    var checked = 0;
    var count = 0;
    var percentage = 0;

    count = $("#check-list input[type=checkbox]").length;
    checked = $("#check-list input[type=checkbox]:checked").length;
    percentage = parseInt((checked / count) * 100, 10);
    if (isNaN(percentage)) {
        percentage = 0;
    }
    $(".custom-label").text(percentage + "%");
    $("#taskProgress").css("width", percentage + "%");

    $("#taskProgress").removeClass("bg-warning");
    $("#taskProgress").removeClass("bg-primary");
    $("#taskProgress").removeClass("bg-success");
    $("#taskProgress").removeClass("bg-danger");

    if (percentage <= 15) {
        $("#taskProgress").addClass("bg-danger");
    } else if (percentage > 15 && percentage <= 33) {
        $("#taskProgress").addClass("bg-warning");
    } else if (percentage > 33 && percentage <= 70) {
        $("#taskProgress").addClass("bg-primary");
    } else {
        $("#taskProgress").addClass("bg-success");
    }
}

var Charts = (function () {
    // Variable

    var $toggle = $('[data-toggle="chart"]');
    var mode = "light"; //(themeMode) ? themeMode : 'light';
    var fonts = {
        base: "Open Sans",
    };

    // Colors
    var colors = {
        gray: {
            100: "#f6f9fc",
            200: "#e9ecef",
            300: "#dee2e6",
            400: "#ced4da",
            500: "#adb5bd",
            600: "#8898aa",
            700: "#525f7f",
            800: "#32325d",
            900: "#212529",
        },
        theme: {
            default: "#172b4d",
            primary: "#5e72e4",
            secondary: "#f4f5f7",
            info: "#11cdef",
            success: "#2dce89",
            danger: "#f5365c",
            warning: "#fb6340",
        },
        black: "#12263F",
        white: "#FFFFFF",
        transparent: "transparent",
    };

    // Methods

    // Chart.js global options
    function chartOptions() {
        // Options
        var options = {
            defaults: {
                global: {
                    responsive: true,
                    maintainAspectRatio: false,
                    defaultColor:
                        mode == "dark" ? colors.gray[700] : colors.gray[600],
                    defaultFontColor:
                        mode == "dark" ? colors.gray[700] : colors.gray[600],
                    defaultFontFamily: fonts.base,
                    defaultFontSize: 13,
                    layout: {
                        padding: 0,
                    },
                    legend: {
                        display: false,
                        position: "bottom",
                        labels: {
                            usePointStyle: true,
                            padding: 16,
                        },
                    },
                    elements: {
                        point: {
                            radius: 0,
                            backgroundColor: colors.theme["primary"],
                        },
                        line: {
                            tension: 0.4,
                            borderWidth: 4,
                            borderColor: colors.theme["primary"],
                            backgroundColor: colors.transparent,
                            borderCapStyle: "rounded",
                        },
                        rectangle: {
                            backgroundColor: colors.theme["warning"],
                        },
                        arc: {
                            backgroundColor: colors.theme["primary"],
                            borderColor:
                                mode == "dark"
                                    ? colors.gray[800]
                                    : colors.white,
                            borderWidth: 4,
                        },
                    },
                    tooltips: {
                        enabled: true,
                        mode: "index",
                        intersect: false,
                    },
                },
                doughnut: {
                    cutoutPercentage: 83,
                    legendCallback: function (chart) {
                        var data = chart.data;
                        var content = "";

                        data.labels.forEach(function (label, index) {
                            var bgColor =
                                data.datasets[0].backgroundColor[index];

                            content += '<span class="chart-legend-item">';
                            content +=
                                '<i class="chart-legend-indicator" style="background-color: ' +
                                bgColor +
                                '"></i>';
                            content += label;
                            content += "</span>";
                        });

                        return content;
                    },
                },
            },
        };

        // yAxes
        Chart.scaleService.updateScaleDefaults("linear", {
            gridLines: {
                borderDash: [2],
                borderDashOffset: [2],
                color: mode == "dark" ? colors.gray[900] : colors.gray[300],
                drawBorder: false,
                drawTicks: false,
                drawOnChartArea: true,
                zeroLineWidth: 0,
                zeroLineColor: "rgba(0,0,0,0)",
                zeroLineBorderDash: [2],
                zeroLineBorderDashOffset: [2],
            },
            ticks: {
                beginAtZero: true,
                padding: 10,
                callback: function (value) {
                    if (!(value % 10)) {
                        return value;
                    }
                },
            },
        });

        // xAxes
        Chart.scaleService.updateScaleDefaults("category", {
            gridLines: {
                drawBorder: false,
                drawOnChartArea: false,
                drawTicks: false,
            },
            ticks: {
                padding: 20,
            },
            maxBarThickness: 10,
        });

        return options;
    }

    // Parse global options
    function parseOptions(parent, options) {
        for (var item in options) {
            if (typeof options[item] !== "object") {
                parent[item] = options[item];
            } else {
                parseOptions(parent[item], options[item]);
            }
        }
    }

    // Push options
    function pushOptions(parent, options) {
        for (var item in options) {
            if (Array.isArray(options[item])) {
                options[item].forEach(function (data) {
                    parent[item].push(data);
                });
            } else {
                pushOptions(parent[item], options[item]);
            }
        }
    }

    // Pop options
    function popOptions(parent, options) {
        for (var item in options) {
            if (Array.isArray(options[item])) {
                options[item].forEach(function (data) {
                    parent[item].pop();
                });
            } else {
                popOptions(parent[item], options[item]);
            }
        }
    }

    // Toggle options
    function toggleOptions(elem) {
        var options = elem.data("add");
        var $target = $(elem.data("target"));
        var $chart = $target.data("chart");

        if (elem.is(":checked")) {
            // Add options
            pushOptions($chart, options);

            // Update chart
            $chart.update();
        } else {
            // Remove options
            popOptions($chart, options);

            // Update chart
            $chart.update();
        }
    }

    // Update options
    function updateOptions(elem) {
        var options = elem.data("update");
        var $target = $(elem.data("target"));
        var $chart = $target.data("chart");

        // Parse options
        parseOptions($chart, options);

        // Toggle ticks
        toggleTicks(elem, $chart);

        // Update chart
        $chart.update();
    }

    // Toggle ticks
    function toggleTicks(elem, $chart) {
        if (
            elem.data("prefix") !== undefined ||
            elem.data("prefix") !== undefined
        ) {
            var prefix = elem.data("prefix") ? elem.data("prefix") : "";
            var suffix = elem.data("suffix") ? elem.data("suffix") : "";

            // Update ticks
            $chart.options.scales.yAxes[0].ticks.callback = function (value) {
                if (!(value % 10)) {
                    return prefix + value + suffix;
                }
            };

            // Update tooltips
            $chart.options.tooltips.callbacks.label = function (item, data) {
                var label = data.datasets[item.datasetIndex].label || "";
                var yLabel = item.yLabel;
                var content = "";

                if (data.datasets.length > 1) {
                    content +=
                        '<span class="popover-body-label mr-auto">' +
                        label +
                        "</span>";
                }

                content +=
                    '<span class="popover-body-value">' +
                    prefix +
                    yLabel +
                    suffix +
                    "</span>";
                return content;
            };
        }
    }

    // Events

    // Parse global options
    if (window.Chart) {
        parseOptions(Chart, chartOptions());
    }

    // Toggle options
    $toggle.on({
        change: function () {
            var $this = $(this);

            if ($this.is("[data-add]")) {
                toggleOptions($this);
            }
        },
        click: function () {
            var $this = $(this);

            if ($this.is("[data-update]")) {
                updateOptions($this);
            }
        },
    });

    // Return

    return {
        colors: colors,
        fonts: fonts,
        mode: mode,
    };
})();

function commonLoader() {
    if ($(".datepicker").length) {
        $(".datepicker").daterangepicker({
            locale: date_picker_locale,
            singleDatePicker: true,
        });
    }

    if ($(".date-rangepicker").length > 0) {
        $(".date-rangepicker").daterangepicker({
            locale: date_picker_locale,
        });
    }

    if ($(".select2").length) {
        $(".select2").select2({
            disableOnMobile: false,
            nativeOnMobile: false,
        });
    }

    if ($(".summernote-simple").length) {
        $(".summernote-simple").summernote({
            dialogsInBody: !0,
            minHeight: 200,
            toolbar: [
                ["style", ["style"]],
                [
                    "font",
                    ["bold", "italic", "underline", "clear", "strikethrough"],
                ],
                ["fontname", ["fontname"]],
                ["color", ["color"]],
                ["para", ["ul", "ol", "paragraph"]],
            ],
        });
    }

    if ($(".jscolor").length > 0) {
        jscolor.installByClassName("jscolor");
    }

    // for Choose file
    $(document).on("change", "input[type=file]", function () {
        var fileclass = $(this).attr("data-filename");
        var finalname = $(this).val().split("\\").pop();
        $("." + fileclass).html(finalname);
    });
}

(function ($, window, i) {
    // Bootstrap 4 Modal
    $.fn.fireModal = function (options) {
        var options = $.extend(
            {
                size: "modal-md",
                center: false,
                animation: true,
                title: "Modal Title",
                closeButton: false,
                header: true,
                bodyClass: "",
                footerClass: "",
                body: "",
                buttons: [],
                autoFocus: true,
                created: function () {},
                appended: function () {},
                onFormSubmit: function () {},
                modal: {},
            },
            options
        );
        this.each(function () {
            i++;
            var id = "fire-modal-" + i,
                trigger_class = "trigger--" + id,
                trigger_button = $("." + trigger_class);
            $(this).addClass(trigger_class);
            // Get modal body
            let body = options.body;
            if (typeof body == "object") {
                if (body.length) {
                    let part = body;
                    body = body
                        .removeAttr("id")
                        .clone()
                        .removeClass("modal-part");
                    part.remove();
                } else {
                    body =
                        '<div class="text-danger">Modal part element not found!</div>';
                }
            }
            // Modal base template
            var modal_template =
                '   <div class="modal' +
                (options.animation == true ? " fade" : "") +
                '" tabindex="-1" role="dialog" id="' +
                id +
                '">  ' +
                '     <div class="modal-dialog ' +
                options.size +
                (options.center ? " modal-dialog-centered" : "") +
                '" role="document">  ' +
                '       <div class="modal-content">  ' +
                (options.header == true
                    ? '         <div class="modal-header">  ' +
                      '           <h5 class="modal-title mx-auto">' +
                      options.title +
                      "</h5>  " +
                      (options.closeButton == true
                          ? '           <button type="button" class="close" data-dismiss="modal" aria-label="Close">  ' +
                            '             <span aria-hidden="true">&times;</span>  ' +
                            "           </button>  "
                          : "") +
                      "         </div>  "
                    : "") +
                '         <div class="modal-body text-center text-dark">  ' +
                "         </div>  " +
                (options.buttons.length > 0
                    ? '         <div class="modal-footer mx-auto">  ' +
                      "         </div>  "
                    : "") +
                "       </div>  " +
                "     </div>  " +
                "  </div>  ";
            // Convert modal to object
            var modal_template = $(modal_template);
            // Start creating buttons from 'buttons' option
            var this_button;
            options.buttons.forEach(function (item) {
                // get option 'id'
                let id = "id" in item ? item.id : "";
                // Button template
                this_button =
                    '<button type="' +
                    ("submit" in item && item.submit == true
                        ? "submit"
                        : "button") +
                    '" class="' +
                    item.class +
                    '" id="' +
                    id +
                    '">' +
                    item.text +
                    "</button>";
                // add click event to the button
                this_button = $(this_button)
                    .off("click")
                    .on("click", function () {
                        // execute function from 'handler' option
                        item.handler.call(this, modal_template);
                    });
                // append generated buttons to the modal footer
                $(modal_template).find(".modal-footer").append(this_button);
            });
            // append a given body to the modal
            $(modal_template).find(".modal-body").append(body);
            // add additional body class
            if (options.bodyClass)
                $(modal_template)
                    .find(".modal-body")
                    .addClass(options.bodyClass);
            // add footer body class
            if (options.footerClass)
                $(modal_template)
                    .find(".modal-footer")
                    .addClass(options.footerClass);
            // execute 'created' callback
            options.created.call(this, modal_template, options);
            // modal form and submit form button
            let modal_form = $(modal_template).find(".modal-body form"),
                form_submit_btn = modal_template.find("button[type=submit]");
            // append generated modal to the body
            $("body").append(modal_template);
            // execute 'appended' callback
            options.appended.call(this, $("#" + id), modal_form, options);
            // if modal contains form elements
            if (modal_form.length) {
                // if `autoFocus` option is true
                if (options.autoFocus) {
                    // when modal is shown
                    $(modal_template).on("shown.bs.modal", function () {
                        // if type of `autoFocus` option is `boolean`
                        if (typeof options.autoFocus == "boolean")
                            modal_form.find("input:eq(0)").focus();
                        // the first input element will be focused
                        // if type of `autoFocus` option is `string` and `autoFocus` option is an HTML element
                        else if (
                            typeof options.autoFocus == "string" &&
                            modal_form.find(options.autoFocus).length
                        )
                            modal_form.find(options.autoFocus).focus(); // find elements and focus on that
                    });
                }
                // form object
                let form_object = {
                    startProgress: function () {
                        modal_template.addClass("modal-progress");
                    },
                    stopProgress: function () {
                        modal_template.removeClass("modal-progress");
                    },
                };
                // if form is not contains button element
                if (!modal_form.find("button").length)
                    $(modal_form).append(
                        '<button class="d-none" id="' +
                            id +
                            '-submit"></button>'
                    );
                // add click event
                form_submit_btn.click(function () {
                    modal_form.submit();
                });
                // add submit event
                modal_form.submit(function (e) {
                    // start form progress
                    form_object.startProgress();
                    // execute `onFormSubmit` callback
                    options.onFormSubmit.call(
                        this,
                        modal_template,
                        e,
                        form_object
                    );
                });
            }
            $(document).on("click", "." + trigger_class, function () {
                $("#" + id).modal(options.modal);
                return false;
            });
        });
    };
    // Bootstrap Modal Destroyer
    $.destroyModal = function (modal) {
        modal.modal("hide");
        modal.on("hidden.bs.modal", function () {});
    };
})(jQuery, this, 0);

$("[data-confirm]").each(function () {
    var me = $(this),
        me_data = me.data("confirm");

    me_data = me_data.split("|");
    me.fireModal({
        title: me_data[0],
        body: me_data[1],
        buttons: [
            {
                text: me.data("confirm-text-yes") || "Yes",
                class: "btn btn-sm btn-danger rounded-pill",
                handler: function () {
                    eval(me.data("confirm-yes"));
                },
            },
            {
                text: me.data("confirm-text-cancel") || "Cancel",
                class: "btn btn-sm btn-secondary rounded-pill",
                handler: function (modal) {
                    $.destroyModal(modal);
                    eval(me.data("confirm-no"));
                },
            },
        ],
    });
});

function postAjax(url, data, cb) {
    var token = $('meta[name="csrf-token"]').attr("content");
    var jdata = { _token: token };

    for (var k in data) {
        jdata[k] = data[k];
    }

    $.ajax({
        type: "POST",
        url: url,
        data: jdata,
        success: function (data) {
            if (typeof data === "object") {
                cb(data);
            } else {
                cb(data);
            }
        },
    });
}

function deleteAjax(url, data, cb) {
    var token = $('meta[name="csrf-token"]').attr("content");
    var jdata = { _token: token };

    for (var k in data) {
        jdata[k] = data[k];
    }

    $.ajax({
        type: "DELETE",
        url: url,
        data: jdata,
        success: function (data) {
            if (typeof data === "object") {
                cb(data);
            } else {
                cb(data);
            }
        },
    });
}

$(function () {

    const getLastDaysDate = (date = '') =>
    {
        let newPrevDate = '';
        if(date)
        {
            newPrevDate = new Date(date);
        }else{
            newPrevDate = new Date();
        }
        let lessDate = newPrevDate.getDate() - 10;
        newPrevDate.setDate(lessDate);
        newPrevDate = formatDate(newPrevDate, "y-m-d");
        return newPrevDate;
    }

    const formatDate = (date, format = "d-m-y") => {
        var d = new Date(date),
            month = "" + (d.getMonth() + 1),
            day = "" + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = "0" + month;
        if (day.length < 2) day = "0" + day;

        if (format == "y-m-d") {
            return [year, month, day].join("-");
        }

        return [day, month, year].join("-");
    };

    var valid = "Yes";
    var ctr = $("#entries tr").length;
    if (ctr == 1) ctr = "";

    $(document).on("click", "#submit_timesheet", function () {
        let date = $("#date").val();
        let project_id = $("#project_id").val();
        let project_value = $("#project_id").find(":selected").text();
        let task_id = $("#task_id").val();
        let task_value = $("#task_id").find(":selected").text();
        let start_time = $("#start_time").val();
        let end_time = $("#end_time").val();
        let remark = $("#remark").val();

        valid = $("#customCheckdef1").is(":checked") ? "Yes" : "No";
        let billableImg = '';

        if($("#customCheckdef1").is(":checked"))
        {
            valid = "Yes";
            billableImg = 'accept.png';
        }else{
            valid = "No";
            billableImg = 'reject.png';
        }
        // <img src=`/public/assets/images/icons/${billableImg}` alt="Yes" height="20" width="20" style="margin-left: 20px;" />

        if (
            project_id === "" ||
            task_id === "" ||
            start_time === "" ||
            end_time === "" ||
            remark === ""
        ) {
            alert("All Fields are required");
            return false;
        }

        //create date format

        var date1 = new Date(date + " " + start_time).getTime();
        var date2 = new Date(date + " " + end_time).getTime();
        var msec = date2 - date1; // Total Difference in Seconds
        var mins = Math.floor(msec / 60000); // Total Difference in Minutes
        var hrs = Math.floor(mins / 60); // Total Difference in Hours
        var totalMins = mins;
        if (mins < 1) {
            alert("Please check your time");
            return false;
        }

        // Update Total Time
        getHrsMinutes(msec);

        mins = mins % 60; // Display Minutes
        let time = `${hrs}h ${mins}m`;

        let displayDate = formatDate(date);

        ctr++;

        $("#showEntries").show();
        $("#entries").append(
            '<tr class="rows" id="row_' +
                ctr +
                '"><td><span>' +
                project_value +
                '</span><input type="hidden" value="' +
                project_id +
                '" name="project_id[]" /></td><td><span>' +
                task_value +
                '</span><input type="hidden" value="' +
                task_id +
                '" name="task_id[]" /></td><td><span>' +
                displayDate +
                '</span><input type="hidden" value="' +
                date +
                '" name="date[]" /></td><td><span>' +
                start_time +
                '</span><input type="hidden" value="' +
                start_time +
                '" name="start_time[]" /></td><td class=""><span>' +
                end_time +
                '</span><input type="hidden" value="' +
                end_time +
                '" name="end_time[]" /><input type="hidden" value="' +
                valid +
                '" name="billable[]" /><input type="hidden" value="' +
                totalMins +
                '" name="total_mins[]" /></td><td><span class="hrs">' +
                hrs +
                '</span>h <span class="mins">' +
                mins +
                "</span>m</td><td><span>" +
                remark +
                '</span><input type="hidden" value="' +
                remark +
                '" name="remark[]" /></td><td>' +
                "<img src='/teamwork/public/assets/images/icons/"+billableImg+"' alt='"+valid+"' height='20' width='20' style='margin-left: 20px;' />" +
                '</td><td><div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center timesheet-remove-log" data-id="' +
                ctr +
                '" data-logged-time="' +
                msec +
                '" data-confirm="Are You Sure" data-text="This action can not be undone. Do you want to continue?" title="Delete" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-trash"></i></span></a></div></td></tr>'
        );
    });
    
    $(document).on("click", "#showMore", function () {
        let prevDate = $("#showMore").attr("data-lastDate");
        let endDate = $("#showMore").attr("data-endDate");
        var projectId = $('select[data-filter="project"]').val();

        var p_url = "./timesheet/showmore";
        var data = {
            lastDate: prevDate,
            projectId : projectId,
            endDate : endDate,
        };
        postAjax(p_url, data, function (res) {
            
            if(res[0]){
                $(".timesheet-entries .noRecords").hide();
                $(".timesheet-entries").append(res[0]);
                if(res[1])
                {
                    $("#showMore").attr("data-lastDate", res[2]);
                }else{
                    $("#showMore").hide()
                }    
            }else{
                $("#showMore").hide()
            }
        });
    });

    $(document).on("click", "#teamShowMore", function () {
        let prevDate = $("#teamShowMore").attr("data-lastDate");
        let datewise = $('#datewise').val();
        let teams = $('#team').val();
        var projectId = $('select[data-filter="project"]').val();
        let endDate = $("#teamShowMore").attr("data-endDate");

        var p_url = "./timesheet/teamshowmore";
        var data = {
            lastDate: prevDate,
            datewise: datewise,
            projectId : projectId,
            teams: teams,
            endDate : endDate,
        };
        postAjax(p_url, data, function (res) {
            
            if(res[0]){
                $(".timesheet-entries .noRecords").hide();
                $(".timesheet-entries").append(res[0]);
                if(res[1])
                {
                    $("#teamShowMore").attr("data-lastDate", res[2]);
                }else{
                    $("#teamShowMore").hide()
                }    
            }else{
                $("#teamShowMore").hide()
            }
        });
    });

    // Script for filter to shot on right sidebar
    jQuery(document).on("click", ".filter-records", function (e) {
        e.preventDefault();
        jQuery(".dash-content").toggleClass("toggled");
    });

    $('[data-toggle="tooltip"]').tooltip({
        container: ".dash-content",
    });
    
});