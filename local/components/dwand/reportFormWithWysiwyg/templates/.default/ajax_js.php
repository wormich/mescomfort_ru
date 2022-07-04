<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(()=>{
        $('[name="text_send"]').addClass('formElement');
    });
    $(document).on('click', '.submitReport', function (){
        let submitObj={};
        let control=true;
        $('.formElement').each(
            (index)=>{
                if($('.formElement').eq(index).val()!==''){
                    submitObj[ $('.formElement').eq(index).attr('name')]=$('.formElement').eq(index).val();
                }else {
                    control=false;
                }

            }
        );

        if(control==true){
            $.ajax({
                type: "POST",
                url: '<?=$templateFolder?>/ajax.php',
                data: submitObj,
                // dataType: "json",
                success: function (result) {

                    if (result == 'success') {
                        $('.result').text('Отзыв отправлен');
                        console.log(result);
                    } else {
                        $('.result').text('Ошибка при отправке данных');
                        console.log(result)
                    }
                },
            });
        }else {
            $('.result').text('Есть незаполненные поля');
        }


    })
    $(document).on('click', '.rating-area input', function (){
        console.log($(this).val());
        $('.trueRating').val($(this).val());

    })
</script>