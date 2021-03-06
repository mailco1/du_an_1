<?php
if (isset($session)) {
    require './pages/modal.php';
}
?>
<div class="load" style="display:none;">
    <img src="../assets/css/patterns/loader.gif">
</div>
<script src="../assets/js/jquery-2.1.1.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/plugins/dataTables/datatables.min.js"></script>
<script src="../assets/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
<script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<!-- Peity -->
<script src="../assets/js/jquery.peity.min.js"></script>

<script src="../assets/js/inspinia.js"></script>
<script src="../assets/js/plugins/pace/pace.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.4/sweetalert2.min.js"></script>

<!-- Peity demo data -->
<script src="../assets/js/peity-demo.js"></script>

<?php if (isset($_SESSION['username'])) : ?>
    <!-- API MAPS -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= $config['KEYMAPAPI']; ?>&callback=initMap"></script>
    <script>
        var map

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: <?= $config['ZOOM']; ?>,
                center: new google.maps.LatLng(<?= $config['TOADOTRUNGTAM']; ?>),
                mapTypeId: 'roadmap'
            });

            var icons = {
                status0: {
                    icon: '../assets/css/patterns/0.png'
                },
                status50: {
                    icon: '../assets/css/patterns/50.png'
                },
                status100: {
                    icon: '../assets/css/patterns/100.png'
                }
            };

            $.ajax({
                url: "data/getMaps",
                dataType: "json",
                success: function(data) {
                    //console.log(data)
                    function addMarker(feature) {
                        var marker = new google.maps.Marker({
                            position: feature.position,
                            icon: icons[feature.type].icon,
                            map: map
                        });
                    }

                    function addInfoWindow(feature) {
                        var infowindow = new google.maps.InfoWindow({
                            content: features.content
                        });
                    }

                    var features = data.map((result, number, arr) => {
                        let str = result['location']
                        string = str.replace(/\s/g, '')
                        let substrings = string.split(",")
                        if (result['garbagepercent'] <= 30) {
                            var status = 'status0'
                        } else if (result['garbagepercent'] <= 80) {
                            var status = 'status50'
                        } else {
                            var status = 'status100'
                        }

                        return {
                            position: {
                                lat: parseFloat(substrings[0]),
                                lng: parseFloat(substrings[1])
                            },
                            type: status,
                            content: 'L??u tr??? ' + result['garbagepercent'] + ' %',
                        }
                    })
                    //console.log(features)
                    for (var i = 0, feature; feature = features[i]; i++) {
                        addMarker(feature);
                        addInfoWindow(feature);
                    }
                },
                error: function() {
                    console.log('l???i');
                }
            });
        }
    </script>
<?php endif; ?>


<!-- Data -->
<script>
    <?php if (isset($_SESSION['username'])) : ?>

        /* Input d??? li???u id user */
        function add(id) {
            $('#iput').val(id);
        }
        /* BACK */
        $("#btn1").on('click', (function(e) {
            $("#content1").show()
            $("#content2").hide()
        }));


        /* $(document).ready(function() {
            const x=[];
            $('#updateTable tbody tr').each(function() {
                const Id = $(this).find("td").eq(3).text()
                x.push({id: Id})
            });
            console.log(x)
        }) */

        /* Get info */
        $(function getUpdate() {
            $.ajax({
                url: "data/getInfo",
                dataType: "json",
                success: function(data) {
                    const list = $('#updateTable tbody tr')
                    $.each(list, function(index, va) {
                        const id = va.getAttribute("id")
                        const newData = data.find(x => x.id === id)
                        const listTD = $(va).find('td').each((i, td) => {
                            const name = td.getAttribute("name")
                            const columnNames = Object.keys(newData)
                            const isUpdate = columnNames.includes(name)
                            if (isUpdate) {
                                $(td).text(newData[name])
                            }
                        })
                    });
                    console.log(data)
                    setTimeout(getUpdate, 5000)
                },
                error: function() {
                    console.log("L???i")
                }
            });
        })


        /* Thay ?????i th??ng tin nh??n vi??n */
        $("#change").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: "data/change",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#lgbtn').text('??ang x??? l??...').prop('disabled', true)
                },
                success: function(data) {
                    //console.log(data)
                    $('#lgbtn').text('Thay ?????i').prop('disabled', false)
                    if (data == true)
                        swal("Th??nh c??ng !", "Thay ?????i ???? ???????c th???c hi???n", "success").then(function() {
                            location.reload();
                        })
                    else if (data == false)
                        swal("L???i !", "Vui l??ng th??? l???i !", "error")
                    else
                        swal("L???i !", "L???i kh??ng x??c ?????nh !", "error")
                },
                error: function() {
                    swal("???? x???y ra l???i!", "???? x???y ra l???i c???c b???, vui l??ng th??? l???i!", "error")
                    $('#lgbtn').text('????ng nh???p').prop('disabled', false)
                }
            });
        }));

        /* TH??M USER */
        $("#Add").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: "data/adduser",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#lgbtn').text('??ang x??? l??...').prop('disabled', true).ready(function() {
                        $("#add").modal('hide');
                    });
                },
                success: function(data) {
                    //console.log(data)
                    $('#lgbtn').text('Th???c hi???n').prop('disabled', false)
                    if (data == true)
                        swal("Th??nh c??ng !", "Th??m th??nh c??ng", "success").then(function() {
                            location.reload();
                        })
                    else if (data == 'null')
                        swal("L???i !", "Vui l??ng ??i???n ????? th??ng tin!", "error")
                    else
                        swal("L???i !", "Vui l??ng th??? l???i !", "error")
                },
                error: function() {
                    swal("???? x???y ra l???i!", "???? x???y ra l???i c???c b???, vui l??ng th??? l???i!", "error")
                    $('#lgbtn').text('Th???c hi???n').prop('disabled', false)
                }
            });
        }));

        /* CH???NH S???A USER */
        $("#Edit").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: "data/edit",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#lgbtn').text('??ang x??? l??...').prop('disabled', true).ready(function() {
                        $("#edit").modal('hide');
                    });
                },
                success: function(data) {
                    //console.log(data)
                    $('#lgbtn').text('Th???c hi???n').prop('disabled', false)
                    if (data == true)
                        swal("Th??nh c??ng !", "S???a th??nh c??ng", "success").then(function() {
                            location.reload();
                        })
                    else if (data == 'null')
                        swal("L???i !", "Vui l??ng ??i???n ????? th??ng tin!", "error")
                    else
                        swal("L???i !", "Vui l??ng th??? l???i !", "error")
                },
                error: function() {
                    swal("???? x???y ra l???i!", "???? x???y ra l???i c???c b???, vui l??ng th??? l???i!", "error")
                    $('#lgbtn').text('Th???c hi???n').prop('disabled', false)
                }
            });
        }));

        /* TH??M TH??NG R??C M???I */
        $("#Addtrash").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: "data/addtrash",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#lgbtn').text('??ang x??? l??...').prop('disabled', true)
                },
                success: function(data) {
                    $('#lgbtn').text('Th??m').prop('disabled', false)
                    if (data == true)
                        swal("Th??nh c??ng !", "Th??m th??nh c??ng", "success").then(function() {
                            location.reload();
                        })
                    else if (data == 'null')
                        swal("L???i !", "Vui l??ng ??i???n ????? th??ng tin!", "error")
                    else
                        swal("L???i !", "M?? token ???? t???n t???i !", "error")
                },
                error: function() {
                    swal("???? x???y ra l???i!", "???? x???y ra l???i c???c b???, vui l??ng th??? l???i!", "error")
                    $('#lgbtn').text('????ng nh???p').prop('disabled', false)
                }
            });
        }));

        /* X??A TH??NG R??C */
        function xoatrash(id) {
            swal({
                title: 'B???n ch???c ch???n ??i???u n??y?',
                //text: alert,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ch???p nh???n',
                cancelButtonText: 'H???y'
            }).then(function() {
                $.ajax({
                    url: "data/xoatrash",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        //console.log(data)
                        if (data == true)
                            swal("Th??nh c??ng", "???? x??a !", "success").then(function() {
                                location.reload();
                            })
                        else
                            swal("L???i !", "X??a kh??ng th??nh c??ng !", "error")

                    },
                    error: function() {
                        swal("???? x???y ra l???i!", "???? x???y ra l???i c???c b???, vui l??ng th??? l???i!", "error")
                    }
                });
            });
        };

        /* XEM CHI TI???T USER */
        function xem(id) {
            $("#content1").hide()
            $("#content2").show()
            $.ajax({
                url: "data/xem",
                type: "POST",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    for (let i = 0; i < 6; i++) {
                        var a = '#vaL' + i;
                        $(a).val(data[i])
                    }
                    $('#vaL6').val(data[6] + ', ' + data[7] + ', ' + data[8] + ', ' + data[9])
                    for (let i = 0; i < 10; i++) {
                        var a = '#val' + i;
                        $(a).val(data[i])
                    }
                },
                error: function() {
                    swal("???? x???y ra l???i!", "???? x???y ra l???i c???c b???, vui l??ng th??? l???i!", "error")
                }
            });
        };

        /* X??A USER */
        $('#xoauser').on('click', function() {
            let id = $('#vaL0').val();
            swal({
                title: 'B???n ch???c ch???n ??i???u n??y?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ch???p nh???n',
                cancelButtonText: 'H???y'
            }).then(function() {
                $.ajax({
                    url: "data/xoauser",
                    type: "POST",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data == true)
                            swal("Th??nh c??ng", "???? x??a !", "success").then(function() {
                                location.reload();
                            })
                        else
                            swal("L???i !", "X??a kh??ng th??nh c??ng !", "error")
                    },
                    error: function() {
                        swal("???? x???y ra l???i!", "???? x???y ra l???i c???c b???, vui l??ng th??? l???i!", "error")
                    }
                });
            });
        });


        /* L???c T??m ki???m */
        $(function() {
            $.getJSON("../assets/vietnam.json", function(data) {
                //console.log(data)
                $('#city').change(function() {
                    var valCity = $(this).val();
                    $('#district').empty();
                    $('#district').append(`<option value="" selected>Ch???n qu???n huy???n</option>`)
                    for (i = 0; i < data[valCity]['districts'].length; i++) {
                        $('#district').append($('<option>', {
                            value: i,
                            text: data[valCity]['districts'][i]['name']
                        }));
                    };

                    $('#district').change(function() {
                        var valDistrict = $(this).val();
                        $('#ward').empty();
                        $('#ward').append(`<option value="" selected>Ch???n ph?????ng x??</option>`)
                        for (j = 0; j < data[valCity]['districts'][valDistrict]['wards'].length; j++) {
                            $('#ward').append($('<option>', {
                                value: j,
                                text: data[valCity]['districts'][valDistrict]['wards'][j]['name']
                            }));
                        };
                    });
                });
            });

            $('#button_search').on('click', function() {
                var keyword = $('#ward :selected').text();
                if (keyword != 'Ch???n ph?????ng x??') {
                    keyword = $('#ward :selected').text();
                } else {
                    keyword = $('#district :selected').text();
                    if (keyword != 'Ch???n qu???n huy???n') {
                        keyword = $('#district :selected').text();
                    } else {
                        keyword = $('#city :selected').text();
                        if (keyword != 'Ch???n t???nh th??nh') {
                            keyword = $('#city :selected').text();
                        } else {
                            keyword = 0;
                        }
                    }
                }
                //console.log(keyword)
                $.ajax({
                    url: "data/search",
                    type: "POST",
                    data: {
                        keyword: keyword
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data['result'] == 'no data found') {
                            $('#result_search').empty();
                            $('#result_search').append(`<h3 class="text-muted text-center">Kh??ng t??m th???y k???t qu???</h3>`)
                        } else {
                            $('#result_search').empty();
                            data.map(function(item) {
                                let id = item['trash_can_id'];
                                $.ajax({
                                    url: "data/trash_can",
                                    type: "POST",
                                    data: {
                                        id: id
                                    },
                                    dataType: 'json',
                                    success: function(data) {
                                        //console.log(data)
                                        $('#result_search').append(`
            <div class="col-lg-4">
                <div class="panel panel-success">
                    <div class="panel-heading text-center">Th??ng tin t??m ki???m</div>
                    <div class="panel-body">
                        <p>T??n: <strong>${item['name']} </strong></p>
                        <p>SDT: ${item['phone']} </p>
                        <p>?????a ch???: ${item['address']}, ${item['ward']}, ${item['district']}, ${item['city']}</p>
                        <P>T???a ????? th??ng r??c: <a href="https://www.google.com/maps/place/${data['location']}" target="_blank">${data['location']}</a></p>
                        <P>C??n n???ng: <strong  style="color:red;">${data['weight']} Kg</strong></p>
                        <P>M???c ?????: <strong  style="color:red;">${data['garbagepercent']} %</strong></p>
                    </div>
                </div>
            </div>`);
                                    },
                                    error: function() {
                                        console.log('l???i')
                                    }
                                })
                            });
                        }
                    },
                    error: function() {
                        console.log('L???i')
                    }
                });
            });
        });


    <?php else : ?>
        /* Preload */
        function loading() {
            $('.load').delay(1000).show().fadeOut('slow')
        }
        /* ????NG NH???P */
        $("#Login").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: "data/login",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#lgbtn').text('??ang x??? l??...').prop('disabled', true)
                },
                success: function(data) {
                    $('#lgbtn').text('????ng nh???p').prop('disabled', false)
                    if (data == true)
                        swal("Th??nh c??ng !", "????ng nh???p th??nh c??ng", "success").then(function() {
                            loading()
                            setTimeout(function() {
                                location.reload()
                            }, 1000)
                        })
                    else if (data == false)
                        swal("L???i ????ng nh???p!", "T??i kho???n ho???c m???t kh???u kh??ng ????ng!", "error")
                    else if (data == 'null')
                        swal("L???i ????ng nh???p!", "Vui l??ng ??i???n ????? th??ng tin!", "error")
                    else
                        swal("L???i ????ng nh???p!", "M??y ch??? kh??ng ph???n h???i d??? li???u!", "error")
                },
                error: function() {
                    swal("???? x???y ra l???i!", "???? x???y ra l???i c???c b???, vui l??ng th??? l???i!", "error")
                    $('#lgbtn').text('????ng nh???p').prop('disabled', false)
                }
            });
        }));
    <?php endif; ?>

    /* TABLE */
    $.fn.dataTable.Buttons.defaults.dom.button.className = 'btn btn-white btn-sm';
    $(document).ready(function() {
        $('table').DataTable({
            pageLength: 10,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [{
                    extend: 'excel',
                    title: 'Danh s??ch qu???n l??'
                },
                {
                    extend: 'pdf',
                    title: 'Danh s??ch qu???n l??'
                },

                {
                    extend: 'print',
                    customize: function(win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ],
            "lengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ],
            "language": {
                "search": "T??m Ki???m",
                "zeroRecords": "Kh??ng t??m th???y k???t qu???",
                "paginate": {
                    "first": "V??? ?????u",
                    "last": "V??? Cu???i",
                    "next": "Ti???n",
                    "previous": "L??i"
                },
                "info": "Hi???n th??? _START_ ?????n _END_ c???a _TOTAL_ m???c",
                "infoEmpty": "Hi???n th??? 0 ?????n 0 c???a 0 m???c",
                "lengthMenu": "Hi???n th??? _MENU_ m???c",
                "infoFiltered": "(???????c l???c t??? _MAX_ M???c)",
                "loadingRecords": "??ang t???i...",
                "emptyTable": "Kh??ng c?? g?? ????? hi???n th???"
            }

        });
    });
</script>

</body>

</html>