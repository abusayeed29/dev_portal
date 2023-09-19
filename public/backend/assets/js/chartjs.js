$(function () {
    "use strict";

    // Bar chart
    if ($("#chartjsBar").length) {
        new Chart($("#chartjsBar"), {
            type: "bar",
            data: {
                labels: ["China", "America", "India", "Germany", "Oman"],
                datasets: [
                    {
                        label: "Population",
                        backgroundColor: [
                            "#b1cfec",
                            "#7ee5e5",
                            "#66d1d1",
                            "#f77eb9",
                            "#4d8af0",
                        ],
                        data: [2478, 5267, 734, 2084, 1433],
                    },
                ],
            },
            options: {
                legend: { display: false },
            },
        });
    }

    if ($("#chartjsLine").length) {
        new Chart($("#chartjsLine"), {
            type: "line",
            data: {
                labels: [
                    1500, 1600, 1700, 1750, 1800, 1850, 1900, 1950, 1999, 2050,
                ],
                datasets: [
                    {
                        data: [
                            86, 114, 106, 106, 107, 111, 133, 221, 783, 2478,
                        ],
                        label: "Africa",
                        borderColor: "#7ee5e5",
                        backgroundColor: "rgba(0,0,0,0)",
                        fill: false,
                    },
                    {
                        data: [
                            282, 350, 411, 502, 635, 809, 947, 1402, 3700, 5267,
                        ],
                        label: "Asia",
                        borderColor: "#f77eb9",
                        backgroundColor: "rgba(0,0,0,0)",
                        fill: false,
                    },
                ],
            },
        });
    }

    if ($("#chartjsDoughnut").length) {
        new Chart($("#chartjsDoughnut"), {
            type: "doughnut",
            data: {
                labels: ["HR", "MIS", "ADMIN"],
                datasets: [
                    {
                        label: "Population (millions)",
                        backgroundColor: ["#7ee5e5", "#f77eb9", "#4d8af0"],
                        data: [2478, 4267, 1334],
                    },
                ],
            },
        });
    }

    if ($("#chartjsGender").length) {
        new Chart($("#chartjsGender"), {
            type: "doughnut",
            data: {
                labels: ["Male", "Female"],
                datasets: [
                    {
                        label: "Population (millions)",
                        backgroundColor: ["#7ee5e5", "#4d8af0"],
                        data: [2478, 500],
                    },
                ],
            },
        });
    }

    if ($("#chartjsArea").length) {
        new Chart($("#chartjsArea"), {
            type: "line",
            data: {
                labels: [
                    1500, 1600, 1700, 1750, 1800, 1850, 1900, 1950, 1999, 2050,
                ],
                datasets: [
                    {
                        data: [
                            86, 114, 106, 106, 107, 111, 133, 221, 783, 2478,
                        ],
                        label: "Africa",
                        borderColor: "#7ee5e5",
                        backgroundColor: "#c2fdfd",
                        fill: true,
                    },
                    {
                        data: [
                            282, 350, 411, 502, 635, 809, 947, 1402, 3700, 5267,
                        ],
                        label: "Asia",
                        borderColor: "#f77eb9",
                        backgroundColor: "#ffbedd",
                        fill: true,
                    },
                ],
            },
        });
    }

    if ($("#chartjsPie").length) {
        new Chart($("#chartjsPie"), {
            type: "pie",
            data: {
                labels: ["Africa", "Asia", "Europe"],
                datasets: [
                    {
                        label: "Population (millions)",
                        backgroundColor: ["#7ee5e5", "#f77eb9", "#4d8af0"],
                        data: [2478, 4267, 1334],
                    },
                ],
            },
        });
    }

    if ($("#chartjsBubble").length) {
        new Chart($("#chartjsBubble"), {
            type: "bubble",
            data: {
                labels: "Africa",
                datasets: [
                    {
                        label: ["China"],
                        backgroundColor: "#c2fdfd",
                        borderColor: "#7ee5e5",
                        data: [
                            {
                                x: 21269017,
                                y: 5.245,
                                r: 15,
                            },
                        ],
                    },
                    {
                        label: ["Denmark"],
                        backgroundColor: "#ffbedd",
                        borderColor: "#f77eb9",
                        data: [
                            {
                                x: 258702,
                                y: 7.526,
                                r: 10,
                            },
                        ],
                    },
                    {
                        label: ["Germany"],
                        backgroundColor: "#bbd4ff",
                        borderColor: "#4d8af0",
                        data: [
                            {
                                x: 3979083,
                                y: 6.994,
                                r: 15,
                            },
                        ],
                    },
                    {
                        label: ["Japan"],
                        backgroundColor: "#ffe69d",
                        borderColor: "#fbbc06",
                        data: [
                            {
                                x: 4931877,
                                y: 5.921,
                                r: 15,
                            },
                        ],
                    },
                ],
            },
            options: {
                scales: {
                    yAxes: [
                        {
                            scaleLabel: {
                                display: true,
                                labelString: "Happiness",
                            },
                        },
                    ],
                    xAxes: [
                        {
                            scaleLabel: {
                                display: true,
                                labelString: "GDP (PPP)",
                            },
                        },
                    ],
                },
            },
        });
    }

    if ($("#chartjsRadar").length) {
        new Chart($("#chartjsRadar"), {
            type: "radar",
            data: {
                labels: [
                    "Africa",
                    "Asia",
                    "Europe",
                    "Latin America",
                    "North America",
                ],
                datasets: [
                    {
                        label: "1950",
                        fill: true,
                        backgroundColor: "#ffbedd",
                        borderColor: "#f77eb9",
                        pointBorderColor: "#f77eb9",
                        pointBackgroundColor: "#ffbedd",
                        data: [8.77, 55.61, 21.69, 6.62, 6.82],
                    },
                    {
                        label: "2050",
                        fill: true,
                        backgroundColor: "#c2fdfd",
                        borderColor: "#7ee5e5",
                        pointBorderColor: "#7ee5e5",
                        pointBackgroundColor: "#c2fdfd",
                        pointBorderColor: "#fff",
                        data: [25.48, 54.16, 7.61, 8.06, 4.45],
                    },
                ],
            },
        });
    }

    if ($("#chartjsPolarArea").length) {
        new Chart($("#chartjsPolarArea"), {
            type: "polarArea",
            data: {
                labels: ["Africa", "Asia", "Europe", "Latin America"],
                datasets: [
                    {
                        label: "Population (millions)",
                        backgroundColor: [
                            "#f77eb9",
                            "#7ee5e5",
                            "#4d8af0",
                            "#fbbc06",
                        ],
                        data: [2478, 5267, 734, 784],
                    },
                ],
            },
        });
    }

    if ($("#chartjsGroupedBar").length) {
        new Chart($("#chartjsGroupedBar"), {
            type: "bar",
            data: {
                labels: ["1900", "1950", "1999", "2050"],
                datasets: [
                    {
                        label: "Africa",
                        backgroundColor: "#f77eb9",
                        data: [133, 221, 783, 2478],
                    },
                    {
                        label: "Europe",
                        backgroundColor: "#7ee5e5",
                        data: [408, 547, 675, 734],
                    },
                ],
            },
        });
    }

    if ($("#chartjsMixedBar").length) {
        new Chart($("#chartjsMixedBar"), {
            type: "bar",
            data: {
                labels: ["1900", "1950", "1999", "2050"],
                datasets: [
                    {
                        label: "Europe",
                        type: "line",
                        borderColor: "#66d1d1",
                        backgroundColor: "rgba(0,0,0,0)",
                        data: [408, 547, 675, 734],
                        fill: false,
                    },
                    {
                        label: "Africa",
                        type: "line",
                        borderColor: "#ff3366",
                        backgroundColor: "rgba(0,0,0,0)",
                        data: [133, 221, 783, 2478],
                        fill: false,
                    },
                    {
                        label: "Europe",
                        type: "bar",
                        backgroundColor: "#f77eb9",
                        // backgroundColor: "rgba(0,0,0,0)",
                        data: [408, 547, 675, 734],
                    },
                    {
                        label: "Africa",
                        type: "bar",
                        backgroundColor: "#7ee5e5",
                        backgroundColorHover: "#3e95cd",
                        // backgroundColor: "rgba(0,0,0,0)",
                        data: [133, 221, 783, 2478],
                    },
                ],
            },
        });
    }

    // asset by type
    if ($("#chartjsDoughnutByAstType").length) {
        new Chart($("#chartjsDoughnutByAstType"), {
            type: "doughnut",
            data: {
                labels: _astTypelabels,
                datasets: [
                    {
                        label: _astTypelabels,
                        backgroundColor: [
                            "#eb3231",
                            "#3893c8",
                            "#c41034",
                            "#e67e22",
                            "#3498db",
                            "#01549a",
                        ],
                        data: _astTypedata,
                    },
                ],
            },
        });
    }
    // asset by company 
    if ($("#chartjsAstByCompany").length) {
      new Chart($("#chartjsAstByCompany"), {
          type: "pie",
          data: {
              labels: _astByComplabels,
              datasets: [
                  {
                      label: "Asset By Company",
                      backgroundColor: [
                          "#eb3231",
                          "#3893c8",
                          "#c41034",
                          "#e67e22",
                          "#3498db",
                          "#01549a",
                          "#00a651",
                          "#204496",
                          "#c24e00",
                          "#0381c1",
                          "#9a121e",
                          "#424067",
                          "#0f5aa8",
                          "#404044",
                          "#bc072c",
                          "#7e7e7e",
                      ],
                      data: _astByCompdata,
                  },
              ],
          },
      });
  }
  //end asset by company 
  if ($("#chartjsAstByStatus").length) {
    new Chart($("#chartjsAstByStatus"), {
        type: "doughnut",
        data: {
            labels: ["Damaged", "Active", "Available"],
            datasets: [
                {
                    label: "Asset Status",
                    backgroundColor: ["#7ee5e5", "#f77eb9", "#4d8af0"],
                    data: [10, 20, 100],
                },
            ],
        },
    });
}
// end asset by status

// Ticekt system

if ($("#chartjsByStatusDate").length) {
    new Chart($("#chartjsByStatusDate"), {
        type: "doughnut",
        data: {
            labels: _clabels,
            datasets: [
                {
                    label: "Population (millions)",
                    backgroundColor: _cbgcolor,
                    data: _cdata,
                },
            ],
        },
    });
}

if ($("#chartjsBarMonthlyTikcet").length) {
    new Chart($("#chartjsBarMonthlyTikcet"), {
        type: "bar",
        data: {
            labels: _mlabels,
            datasets: [
                {
                    label: "Tickets",
                    backgroundColor: [
                        "#b1cfec",
                        "#7ee5e5",
                        "#66d1d1",
                        "#f77eb9",
                        "#4d8af0",
                    ],
                    data: _mdata,
                },
            ],
        },
        options: {
            legend: { display: false },
        },
    });
}

if ($("#chartjsLineDailyTkt").length) {
    new Chart($("#chartjsLineDailyTkt"), {
        type: "line",
        data: {
            labels: _wlabels,
            datasets: [
                {
                    label: "Ticket",
                    data: _wdata,
                    fill: false,
                    borderColor: "rgb(75, 192, 192)",
                    tension: 0.1,
                },
            ],
        },
    });
}

if ($("#chartjsLineDailyTktByEngineer").length) {
    new Chart($("#chartjsLineDailyTktByEngineer"), {
        type: "line",
        data: {
            labels: _daylabels,
            datasets: [
                {
                    label: "Ticket",
                    data: _daydata,
                    fill: false,
                    borderColor: "rgb(75, 192, 192)",
                    tension: 0.1,
                },
            ],
        },
    });
}

if ($("#chartjsDoughnutEngTktsByStatus").length) {
    new Chart($("#chartjsDoughnutEngTktsByStatus"), {
        type: "doughnut",
        data: {
            labels: _englabels,
            datasets: [
                {
                    label: "Population (millions)",
                    backgroundColor: _engbgcolor,
                    data: _engdata,
                },
            ],
        },
    });
}

if ($("#chartjsDoughnutByStatus").length) {
    new Chart($("#chartjsDoughnutByStatus"), {
        type: "pie",
        data: {
            labels: _labels,
            datasets: [
                {
                    label: "Population (millions)",
                    backgroundColor: _bgcolor,
                    data: _data,
                },
            ],
        },
    });
}
if ($("#chartjsDoughnutByCompany").length) {
    new Chart($("#chartjsDoughnutByCompany"), {
        type: "pie",
        data: {
            labels: _cmplabels,
            datasets: [
                {
                    label: "Population (millions)",
                    backgroundColor: [
                        "#eb3231",
                        "#3893c8",
                        "#c41034",
                        "#e67e22",
                        "#3498db",
                        "#01549a",
                        "#00a651",
                        "#204496",
                        "#c24e00",
                        "#0381c1",
                        "#9a121e",
                        "#424067",
                        "#0f5aa8",
                        "#404044",
                        "#bc072c",
                        "#7e7e7e",
                    ],
                    data: _cmpdata,
                },
            ],
        },
    });
}

if ($("#chartjsDoughnutByCompanyToday").length) {
    new Chart($("#chartjsDoughnutByCompanyToday"), {
        type: "pie",
        data: {
            labels: _tcmplabels,
            datasets: [
                {
                    label: "Population (millions)",
                    backgroundColor: [
                        "#eb3231",
                        "#3893c8",
                        "#c41034",
                        "#e67e22",
                        "#3498db",
                        "#01549a",
                        "#00a651",
                        "#204496",
                        "#c24e00",
                        "#0381c1",
                        "#9a121e",
                        "#424067",
                        "#0f5aa8",
                        "#404044",
                        "#bc072c",
                        "#7e7e7e",
                    ],
                    data: _tcmpdata,
                },
            ],
        },
    });
}

// end ticket system

// Start SLA
if ($("#slaFailByDepartment").length) {
    new Chart($("#slaFailByDepartment"), {
        type: "doughnut",
        data: {
            labels: ["HR", "MIS", "ADMIN"],
            datasets: [
                {
                    label: "Population (millions)",
                    backgroundColor: ["#7ee5e5", "#f77eb9", "#4d8af0"],
                    data: [2478, 4267, 1334],
                },
            ],
        },
    });
}


if ($("#slaSuccessByDepartment").length) {
    new Chart($("#slaSuccessByDepartment"), {
        type: "line",
        data: {
            labels: [
                '22-08-17', '22-08-18','22-08-19','22-08-20','22-08-21', '22-08-22', '22-08-23', '22-08-24', '22-08-25','22-08-26'
            ],
            datasets: [
                {
                    data: [
                        86, 114, 106, 106, 107, 111, 133, 221, 783, 2478,
                    ],
                    label: "HR",
                    borderColor: "#7ee5e5",
                    backgroundColor: "rgba(0,0,0,0)",
                    fill: false,
                },
                {
                    data: [
                        282, 350, 411, 502, 635, 809, 947, 1402, 3700, 5267,
                    ],
                    label: "MIS",
                    borderColor: "#f77eb9",
                    backgroundColor: "rgba(0,0,0,0)",
                    fill: false,
                },
                {
                    data: [
                        500, 900, 1000, 1500, 2685, 2700, 2947, 4152, 4500, 7067,
                    ],
                    label: "ADMIN",
                    borderColor: "#0d6efd",
                    backgroundColor: "rgba(0,0,0,0)",
                    fill: false,
                },
            ],
        },
    });
}
// end SLA

    
});
