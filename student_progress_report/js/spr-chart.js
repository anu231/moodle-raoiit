                                window.onload = function() {
                                        // For Performance in Paper 
                                        // graph-1
                                            var ctx1 = document.getElementById("canvas1").getContext("2d");
                                            window.myBar = new Chart(ctx1, {
                                                type: 'bar',
                                                data: barChartData1,
                                                options: {
                                                    responsive: true,
                                                    legend: {
                                                        position: 'top',
                                                    },
                                                    title: {
                                                        display: true,
                                                        text: 'Performance in Paper'
                                                    },
                                                    scales: {
                                                        yAxes: [{
                                                            ticks: {
                                                                //beginAtZero:true
                                                                min:0
                                                            }
                                                        }],
                                                        xAxes: [{
                                                            ticks: {
                                                                //beginAtZero:true
                                                                min:0
                                                            }
                                                        }]
                                                    }
                                                }
                                            });


                                        // For Performance Analysis in Percentage 
                                        // graph-2
                                            var ctx2 = document.getElementById("canvas2").getContext("2d");
                                            window.myBar = new Chart(ctx2, {
                                                type: 'bar',
                                                data: barChartData2,
                                                options: {
                                                    responsive: true,
                                                    legend: {
                                                        position: 'top',
                                                    },
                                                    title: {
                                                        display: true,
                                                        text: 'Performance Analysis in Percentage'
                                                    },
                                                    scales: {
                                                        yAxes: [{
                                                            ticks: {
                                                                //beginAtZero:true
                                                                min:0
                                                            }
                                                        }],
                                                        xAxes: [{
                                                            ticks: {
                                                                //beginAtZero:true
                                                                min:0
                                                            }
                                                        }]
                                                    }
                                                }
                                            });

                                        // For Marks Analysis    
                                        // graph-3
                                            var ctx3 = document.getElementById("canvas3").getContext("2d");
                                            window.myBar = new Chart(ctx3, {
                                                type: 'bar',
                                                data: barChartData3,
                                                options: {
                                                    responsive: true,
                                                    legend: {
                                                        position: 'top',
                                                    },
                                                    title: {
                                                        display: true,
                                                        text: 'Marks Analysis'
                                                    }
                                                }
                                            });

                                            // For Students Performance in Physics  
                                            // graph-4
                                            var ctx4 = document.getElementById("canvas4").getContext("2d");
                                            window.myBar = new Chart(ctx4, {
                                                type: 'bar',
                                                data: barChartData4,
                                                options: {
                                                    responsive: true,
                                                    legend: {
                                                        position: 'top',
                                                    },
                                                    title: {
                                                        display: true,
                                                        text: 'Students Performance in Physics'
                                                    },
                                                    scales: {
                                                        yAxes: [{
                                                            ticks: {
                                                                //beginAtZero:true
                                                                min:0
                                                            }
                                                        }],
                                                        xAxes: [{
                                                            ticks: {
                                                                //beginAtZero:true
                                                                min:0
                                                            }
                                                        }]
                                                    }
                                                }
                                            });


                                        // For Physics Analysis 
                                        // graph-5
                                            var ctx5 = document.getElementById("canvas5").getContext("2d");
                                            window.myBar = new Chart(ctx5, {
                                                type: 'bar',
                                                data: barChartData5,
                                                options: {
                                                    responsive: true,
                                                    legend: {
                                                        position: 'top',
                                                    },
                                                    title: {
                                                        display: true,
                                                        text: 'Physics Analysis'
                                                    },
                                                    scales: {
                                                        yAxes: [{
                                                            ticks: {
                                                                //beginAtZero:true
                                                                min:0
                                                            }
                                                        }],
                                                        xAxes: [{
                                                            ticks: {
                                                                //beginAtZero:true
                                                                min:0
                                                            }
                                                        }]
                                                    }
                                                }
                                            });

                                        // For Marks Analysis -Physics  
                                        // graph-6
                                            var ctx6 = document.getElementById("canvas6").getContext("2d");
                                            window.myBar = new Chart(ctx6, {
                                                type: 'bar',
                                                data: barChartData6,
                                                options: {
                                                    responsive: true,
                                                    legend: {
                                                        position: 'top',
                                                    },
                                                    title: {
                                                        display: true,
                                                        text: 'Marks Analysis - Physics'
                                                    }
                                                }
                                            });

                                            // For Students Performance in Chemistry  
                                            // graph-7
                                            var ctx7 = document.getElementById("canvas7").getContext("2d");
                                            window.myBar = new Chart(ctx7, {
                                                type: 'bar',
                                                data: barChartData7,
                                                options: {
                                                    responsive: true,
                                                    legend: {
                                                        position: 'top',
                                                    },
                                                    title: {
                                                        display: true,
                                                        text: 'Students Performance in Chemistry'
                                                    },
                                                    scales: {
                                                        yAxes: [{
                                                            ticks: {
                                                                //beginAtZero:true
                                                                min:0
                                                            }
                                                        }],
                                                        xAxes: [{
                                                            ticks: {
                                                                //beginAtZero:true
                                                                min:0
                                                            }
                                                        }]
                                                    }
                                                }
                                            });


                                        // For Chemistry Analysis 
                                        // graph-8
                                            var ctx8 = document.getElementById("canvas8").getContext("2d");
                                            window.myBar = new Chart(ctx8, {
                                                type: 'bar',
                                                data: barChartData8,
                                                options: {
                                                    responsive: true,
                                                    legend: {
                                                        position: 'top',
                                                    },
                                                    title: {
                                                        display: true,
                                                        text: 'Chemistry Analysis'
                                                    },
                                                    scales: {
                                                        yAxes: [{
                                                            ticks: {
                                                                //beginAtZero:true
                                                                min:0
                                                            }
                                                        }],
                                                        xAxes: [{
                                                            ticks: {
                                                                //beginAtZero:true
                                                                min:0
                                                            }
                                                        }]
                                                    }
                                                }
                                            });

                                        // For Marks Analysis -Chemistry  
                                        // graph-9
                                            var ctx9 = document.getElementById("canvas9").getContext("2d");
                                            window.myBar = new Chart(ctx9, {
                                                type: 'bar',
                                                data: barChartData9,
                                                options: {
                                                    responsive: true,
                                                    legend: {
                                                        position: 'top',
                                                    },
                                                    title: {
                                                        display: true,
                                                        text: 'Marks Analysis - Chemistry'
                                                    }
                                                }
                                            });

                                            // For Students Performance in Chemistry  
                                            // graph-10
                                            var ctx10 = document.getElementById("canvas10").getContext("2d");
                                            window.myBar = new Chart(ctx10, {
                                                type: 'bar',
                                                data: barChartData10,
                                                options: {
                                                    responsive: true,
                                                    legend: {
                                                        position: 'top',
                                                    },
                                                    title: {
                                                        display: true,
                                                        text: 'Students Performance in Mathematics'
                                                    },
                                                    scales: {
                                                        yAxes: [{
                                                            ticks: {
                                                                //beginAtZero:true
                                                                min:0
                                                            }
                                                        }],
                                                        xAxes: [{
                                                            ticks: {
                                                                //beginAtZero:true
                                                                min:0
                                                            }
                                                        }]
                                                    }
                                                }
                                            });


                                        // For Chemistry Analysis 
                                        // graph-11
                                            var ctx11 = document.getElementById("canvas11").getContext("2d");
                                            window.myBar = new Chart(ctx11, {
                                                type: 'bar',
                                                data: barChartData11,
                                                options: {
                                                    responsive: true,
                                                    legend: {
                                                        position: 'top',
                                                    },
                                                    title: {
                                                        display: true,
                                                        text: 'Mathematics Analysis'
                                                    },
                                                    scales: {
                                                        yAxes: [{
                                                            ticks: {
                                                                //beginAtZero:true
                                                                min:0
                                                            }
                                                        }],
                                                        xAxes: [{
                                                            ticks: {
                                                                //beginAtZero:true
                                                                min:0
                                                            }
                                                        }]
                                                    }
                                                }
                                            });

                                        // For Marks Analysis -Chemistry  
                                        // graph-12
                                            var ctx12 = document.getElementById("canvas12").getContext("2d");
                                            window.myBar = new Chart(ctx12, {
                                                type: 'bar',
                                                data: barChartData12,
                                                options: {
                                                    responsive: true,
                                                    legend: {
                                                        position: 'top',
                                                    },
                                                    title: {
                                                        display: true,
                                                        text: 'Marks Analysis - Mathematics'
                                                    }
                                                }
                                            });


                                        };

