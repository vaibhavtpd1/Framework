<?php $this->setSiteTitle("Signup"); ?>

<?php $this->start('head'); ?>

<?php $this->end(); ?>

<?php $this->start('body'); ?>
            <form method="POST" action="register">
                  <table style="border-spacing: 30px; text-align: center;">
                        <tr>
                          <td>Name:</td>
                          <td><input type="text" name="name"></td>
                        </tr>
                        <tr>
                          <td>Email ID:</td>
                          <td><input type="email" name="email"></td>
                        </tr>
                        <tr>
                          <td>Password:</td>
                          <td><input type="password" name="password"></td>
                        </tr>
                        <tr>
                          <td>Confirm Password:</td>
                          <td><input type="Password" name="confirmpassword"></td>
                        </tr>
                        <tr>
                          <td colspan="2"><input type="Submit" value="SignUp"></td>
                        </tr>
                  </table>
            </form>
<?php $this->end(); ?>
