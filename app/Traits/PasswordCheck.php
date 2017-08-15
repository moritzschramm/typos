<?php

namespace App\Traits;

trait PasswordCheck {

  /**
    * checks if a password is too weak
    * criterea:
    * - min 8 characters
    * - not in weak password list
    *
    * @param $password
    * @return boolean: if password is to weak to accept
    */
  public function is_weak_password($password)
  {
    # list of weak passwords
    $weak_passwords = ['password', '12345678','123456789','baseball','football','qwertyuiop','1234567890','superman','1qaz2wsx','trustno1','jennifer','sunshine','iloveyou','starwars','computer','michelle','11111111','princess','987654321','corvette','1234qwer','88888888','q1w2e3r4t5','internet','samantha','whatever','maverick','steelers','mercedes','123123123','qwer1234','hardcore','q1w2e3r4','midnight','bigdaddy','victoria','1q2w3e4r','cocacola','marlboro','asdfasdf','87654321','12344321','jordan23','Password','jonathan','liverpoo','danielle','abcd1234','scorpion','qazwsxedc','password1','slipknot','qwerty123','startrek','12341234','redskins','butthead','asdfghjkl','qwertyui','liverpool','dolphins','nicholas','elephant','mountain','xxxxxxxx','1q2w3e4r5t','metallic','shithead','benjamin','creative','rush2112','asdfghjk','4815162342','passw0rd','bullshit','1qazxsw2','garfield','01012011','69696969','december','11223344','godzilla','airborne','lifehack','brooklyn','platinum','darkness','blink182','789456123','12qwaszx','snowball','pakistan','redwings','williams','nintendo','guinness','november','minecraft','asdf1234','lasvegas','babygirl','dickhead','12121212','147258369','explorer','snickers','metallica','alexande','paradise','michigan','carolina','lacrosse','christin','kimberly','kristina','0987654321','poohbear','bollocks','qweasdzxc','drowssap','caroline','einstein','spitfire','maryjane','1232323q','champion','svetlana','westside','courtney','12345qwert','patricia','aaaaaaaa','anderson','security','stargate','simpsons','scarface','123456789a','1234554321','cherokee','Usuckballz1','veronica','semperfi','scotland','marshall','qwerty12','98765432','softball','passport','franklin','alexander','55555555','zaq12wsx','infinity','kawasaki','77777777','vladimir','freeuser','wildcats','budlight','brittany','00000000','bulldogs','swordfis','PASSWORD','patriots','pearljam','colorado','ncc1701d','motorola','logitech','juventus','wolverin','warcraft','hello123','peekaboo','123654789','panthers','elizabet','spiderma','virginia','valentin','predator','mitchell','741852963','1111111111','rolltide','changeme','lovelove','fktrcfylh','loverboy','chevelle','cardinal','michael1','147852369','american','alexandr','electric','wolfpack','spiderman','darkside','123456789q','01011980','freepass',];


    if(strlen($password) < 8 || in_array($password, $weak_passwords)) {

      return true;
    }

    return false;
  }
}
