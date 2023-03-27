<?php
    $cn = new mysqli(
        "localhost",
        "root",
        "",
        "php_test1"
    );
    $id = 1;
    $sql = "SELECT MAX(id) FROM tbl_test";
    $rs = $cn->query($sql);
    if ($rs->num_rows>0) {
        $row = $rs->fetch_array();
        $id = $row[0]+1;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="test.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script
			  src="https://code.jquery.com/jquery-3.6.4.min.js"
			  integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="
			  crossorigin="anonymous"></script>

    <style>
        .img-box{
            background-image: url("image.bmp");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            width: 100px;
            height: 100px;
            border: 1px solid #ccc;
            float: left;
            margin: 10px;
        }
    </style>
</head>
<body>
    <form class="upl">
        <div class="frm">
            <label for="">ID</label>
            <input type="text" name="txt-id" id = "txt-id" class="frm-control" value = "<?php echo $id;?>">
        
            <label for="">Name</label>
            <input type="text" name="txt-name" id="txt-name" class="frm-control">
        
            <label for="">Price</label>
            <input type="text" name="txt-price" id="txt-price" class="frm-control">

            <div class="img-box">
                <input style="opacity: 0;" class = "txt-file" type="file" name="txt-file" id="txt-file">
                
                <div class = "img-loading">
                </div>
            </div>
            <input type="text" name = "txt-img" id = "txt-img" class = "frm-control">

            <div class = "btn-save">
                Save
            </div>
        </div>
    </form>
    <table class = "table" id="tblData">
        <tr>
            <th width="100">ID</th>
            <th>Name</th>
            <th width="100">Price</th>
            <th width = "100">Photo</th>
        </tr>

        <?php
            $sql = "SELECT * from tbl_test";
            $rs = $cn->query($sql);
            while($row = $rs->fetch_array()){
                ?>
                <tr>
                    <td><?php echo $row[0];?></td>
                    <td><?php echo $row[1];?></td>
                    <td><?php echo $row[2];?></td>
                    <td><?php echo $row[4];?></td>
                </tr>
                <?php
            }
        ?>
    </table>
    
</body>
    <script>
        $(document).ready(function(){
            var loading = "<div class = 'img-loading'></div>"
            var tbl = $('#tblData');
            //upload image
            $('.txt-file').change(function(){
                var eThis = $(this);
                var imgBox = $(".img-box");
                var frm = eThis.closest('form.upl');
                var frm_data = new FormData(frm[0]);
                $.ajax({
                    url:'upl-image.php',
                    type:'POST',
                    data:frm_data,
                    contentType:false,
                    cache:false,
                    processData:false,
                    dataType:"json",
                    beforeSend:function(){
                        imgBox.append(loading);
                    },
                    success:function(data){     
                        imgBox.css({"background-image": "url(image/"+data['imageName']+"",}); 
                        imgBox.find('.img-loading');
                        imgBox.find('.txt-img').val(data['imageName']);
                        $('#txt-img').val(data['imageName']);
                  }
                })
            });
            $('.btn-save').click(function(){
                var eThis = $(this);
                var id = $('#txt-id');
                var name = $('#txt-name');
                var price = $('#txt-price');
                if (name.val() == '') {
                    alert('Please Input Name');
                    name.focus();
                    return;
                }else if(price.val() == ''){
                    alert('Please Input Price');
                    price.focus();
                    return;
                }
                var frm = eThis.closest('form.upl');
                var frm_data = new FormData(frm[0]);
                $.ajax({
                    url:'save.php',
                    type:'POST',
                    data:frm_data,
                    contentType:false,
                    cache:false,
                    processData:false,
                    dataType:"json",
                    beforeSend:function(){
                        eThis.html('wating....');
                    },
                    success:function(data){
                        if(data['dpl'] == true){
                            alert("Dupliate name");
                        }else{
                            var tr = `
                            <tr>
                                 <td>${id.val()}</td>
                                 <td>${name.val()}</td>
                                 <td>${price.val()}</td>

                            </tr>
                        `;
                        }
                        tbl.find('tr:eq(0)').after(tr);
                        // tbl.prepend(tr);

                        eThis.html('Save');
                        name.val("");
                        price.val("");
                        id.val(data['id']+1);
                        name.focus();
                    }
                });
            });
        })
    </script>
</html>