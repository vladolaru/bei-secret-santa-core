<?php
class SecretSantaCore
{
    protected $fromEmail = null;
    protected $emailTitle = null;
    protected $recommendedExpenses = null;
    protected $users = array();
    protected $sentEmailsAddresses = array();

    public function setMailFrom( $email ) {
        if( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
            $this->fromEmail = $email;
        } else {
            echo "Emailul: $email este invalid!" . "<br>";
        }
    }

    public function setEmailTitle( $title ) {
        $tempTitle = str_split( $title );
        foreach ( $tempTitle as $char ){
            if( !(  ( $char >= 'A' && $char <= 'Z' ) || ( $char >= 'a' && $char <='z' ) ||
                    ( $char >= '0' && $char <= '9' ) || ( $char == ' ' || $char == '!' || $char == '.' ) ) ) {
                    echo "Titlul $title nu este valid!" . "<br>";
                    break;
            }
        }
        $this->emailTitle = $title;
    }

    public function setRecommendedExpenses( $allocatedSum ) {
        if( is_numeric( $allocatedSum ) ) {//true si in cazul stringurilor numerice
            if( $allocatedSum > 0 ) {
                $this->recommendedExpenses = $allocatedSum;
            } else {
                echo "recommendedExpenses nu poate fi mai mic ca zero!" . "<br>";
            }
        } else {
            echo "recommendedExpenses trebuie sa fie un numar!" . "<br>";
        }
    }

    public function addUsers( $newUsers ) {
        foreach ( $newUsers as $newUser ) {
            if ( $this->checkParticipant( $newUser ) ) {
                if( !$this->participantExists( $newUser ) ) {
                    $this->addUser($newUser);
                } else {
                    echo $newUser[1] . " emailul este deja folosit!" . "<br>";
                }
            } else {
                echo $newUser[0] . " Nu a fost adaugat!" . "<br>";
            }
        }
    }

    public function goRudolph() {
        if ( $this->checkIfReady() ) {
            $colleagues = array();

            foreach ( $this->users as $pos => $user ) {
                $colleagues[$pos] = $pos;
            }

            $noParticipants = count( $this->users );
            for ( $i = 0; $i < $noParticipants - 1; $i++ ){
                $random = rand($i + 1, $noParticipants - 1);

                $aux = $colleagues[$i];
                $colleagues[$i] = $colleagues[$random];
                $colleagues[$random] = $aux;
            }

            foreach( $this->users as $key => $user ) {
                if(
                    mail( $user['email'], $this->emailTitle,
                        "Draga " . $user['name'] . ",\r\nTrebuie sa ii iei cadou lui " .
                        $this->users[$colleagues[$key]]['name'] . " cu emailul " . $this->users[$colleagues[$key]]['email'] .
                        " in valoare de " . $this->recommendedExpenses . " lei!",
                        "From: " . $this->fromEmail
                        )
                ) {
                    array_push($this->sentEmailsAddresses, $user['email']);
                }
            }
        }

    }

    public function getSentEmailsAddresses() {
        return $this->sentEmailsAddresses;
    }


    protected function checkIfReady() {
        if( empty( $this->fromEmail ) ) {
            echo "error: emailTitle nu poate lipsi!" . "<br>";
            return false;
        }

        if( empty( $this->recommendedExpenses ) ) {
            echo "error: recommendedExpenses nu poate lipsi!" . "<br>";
            return false;
        }

        if( empty( $this->emailTitle ) ) {
            $this->emailTitle = 'No title';
            echo "warning: emailTitle nu a fost setat si se va folosi titlul \'No title\'" . "<br>";
        }

        if( count( $this->users ) < 2) {
            echo "error: numar invalid de participanti!" . "<br>";
            return false;
        }

        return true;
    }

    protected function addUser( $user ) {
        $position = count($this->users);
        $this->users[$position]['name'] = $user[0];
        $this->users[$position]['email'] = $user[1];
    }

    protected function checkParticipant( $participant ) {
        if( count ( $participant ) != 2 ) {
            return false;
        }

        if ( !ctype_alpha( $participant[0] ) ) {
            return false;
        }

        if( !filter_var( $participant[1], FILTER_VALIDATE_EMAIL ) ) {
            return false;
        }

        return true;
    }

    protected function participantExists( $participant ) {
        foreach ( $this->users as $user){
            if( $user['email'] == $participant[1] ) {
                return true;
            }
        }
        return false;
    }
}


