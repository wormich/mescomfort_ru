<script>
    var
        globalResultID = null,
        btnSandEmail = {
            title: 'Отправить E-mail уведомление',
            id: 'sandbtn',
            name: 'sandbtn',
            className: BX.browser.IsIE() && BX.browser.IsDoctype() && !BX.browser.IsIE10() ? '' : 'adm-btn-save',
            action: function () {
                if (!confirm('Отправить?')) return false;
                BX.ajax({
                    method: 'GET',
                    dataType: 'html',
                    url: '/local/modules/dw.devino/api/send_email.php?id=' + globalResultID + '&send=Y',
                    onsuccess: function (data) {
                        alert(data);
                        emailDialog.Close();
                        globalResultID = null;
                    },
                    onfailure: function () {
                        alert('Возникла ошибка');
                    }
                });
            }
        },
        btnSandSms = {
            title: 'Отправить SMS уведомление',
            id: 'sandbtn',
            name: 'sandbtn',
            className: BX.browser.IsIE() && BX.browser.IsDoctype() && !BX.browser.IsIE10() ? '' : 'adm-btn-save',
            action: function () {
                if (!confirm('Отправить?')) return false;
                BX.ajax({
                    method: 'GET',
                    dataType: 'html',
                    url: '/local/modules/dw.devino/api/send_sms.php?id=' + globalResultID + '&send=Y',
                    onsuccess: function (data) {
                        alert(data);
                        BX('show_result_sms').innerHTML = 'Загрузка...';
                        send_sms(globalResultID);
                    },
                    onfailure: function () {
                        alert('Возникла ошибка');
                    }
                });
            }
        },
        btnStatusSms = {
            title: 'Обновить статусы',
            id: 'statusbtn',
            name: 'statusbtn',
            className: BX.browser.IsIE() && BX.browser.IsDoctype() && !BX.browser.IsIE10() ? '' : 'adm-btn-save',
            action: function () {
                BX('show_result_sms').innerHTML = 'Загрузка...';
                send_sms(globalResultID);
            }
        },
        emailDialog = new BX.CDialog({
            title: 'E-mail уведомления',
            content: '<div id="show_result_email"></div>',
            icon: 'head-block',
            resizable: true,
            draggable: true,
            width: '800',
            height: '400',
            buttons: [
                btnSandEmail,
                BX.CDialog.btnClose
            ]
        }),
        smsDialog = new BX.CDialog({
            title: 'SMS уведомления',
            content: '<div id="show_result_sms"></div>',
            icon: 'head-block',
            resizable: true,
            draggable: true,
            width: '800',
            height: '400',
            buttons: [
                btnSandSms,
                btnStatusSms,
                BX.CDialog.btnClose
            ]
        });

    function send_email(id) {
        globalResultID = id;
        BX.ready(function () {
            BX.ajax({
                method: 'GET',
                dataType: 'html',
                url: '/local/modules/dw.devino/api/send_email.php?id=' + id,
                onsuccess: function (data) {
                    BX('show_result_email').innerHTML = data;
                    emailDialog.Show();
                },
                onfailure: function () {
                    alert('Возникла ошибка');
                }
            });
        })
    }

    function send_sms(id) {
        globalResultID = id;
        BX.ready(function () {
            BX.ajax({
                method: 'GET',
                dataType: 'html',
                url: '/local/modules/dw.devino/api/send_sms.php?id=' + id,
                data: {id: id},
                onsuccess: function (data) {
                    BX('show_result_sms').innerHTML = data;
                    smsDialog.Show();
                },
                onfailure: function () {
                    alert('Возникла ошибка');
                }
            });
        })
    }
</script>
<?php
unset($adminMenu->aGlobalMenu["global_menu_content"]);
