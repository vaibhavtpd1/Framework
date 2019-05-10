<?php
$this->start('body'); ?>
<h1>WELCOME TO DASHBOARD</h1>
<h1><?php
echo "email: ". ($this->dataArray[0]->email);
echo "Full Name: ". ($this->dataArray[0]->name);
?><h1>
<a href = "../login/logout">Logout</a>
<a href = "../user/deleteAccount">Delete Account</a>
<?php $this->end() ?>
