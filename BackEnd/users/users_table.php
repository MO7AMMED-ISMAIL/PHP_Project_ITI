<?php
    if(!isset($_SESSION['id'])){
        if(!isset($_SESSION['id'])){
        header("location: ../404.php");
    }
}
?>
<div class="container text-black"> <!-- Set the text color to black -->
        <h1 class="text-center mb-5">All Users</h1> <!-- Display "All Users" at the top of the page -->
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="container-fluid">
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <a href="?add=User" class="btn btn-primary">Add</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Room</th>
                                            <th>Img</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php 
                                        foreach ($users as $user){
                                            
                                    ?>
                                        <tr>
                                            <td><?=$id++?></td>
                                            <td>
                                                <?= $user['username'] ?>
                                            </td>
                                            <td>
                                                <?= $user['email'] ?>
                                            </td>
                                            <?php
                                                $userRoom = $room->FindById('id', $user['room_id']);
                                            ?>
                                            <td>
                                                <a href=""><?= $userRoom['room_number']?></a>
                                            </td>
                                            
                                            <td>
                                                <img src="uploads/<?= $user['profile_picture'] ?>" style="max-width:50px; max-height:50px;">
                                            </td>

                                            <td>
                                                <a href="?edit=<?=$user['id']?>" class='btn btn-warning btn-sm'>Edit</a>
                                                <a href='users/delet.php?id=<?=$user['id'] ?>' class='btn btn-danger btn-sm'>Delete</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>