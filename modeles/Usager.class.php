<?php
/**
 * Created by PhpStorm.
 * User: damien
 * Date: 13/03/2019
 * Time: 09:58
 */

class Usager extends Modele
{
    const TABLE = 'vino__usager';

    /**
     * @param $mail
     * @param $pseudo
     * @return bool
     */
    public function existUsager($mail,$pseudo){
        try {
            #Prepare stmt or reports errors

            $stmt = $this->_db->prepare("SELECT * FROM " . self::TABLE. " WHERE mail = ? OR pseudo = ?" ) or trigger_error($stmt->error, E_USER_ERROR);;
            $stmt ->bind_param("ss",$mail,$pseudo );

            #Execute stmt or reports errors
            $stmt->execute() or trigger_error($stmt->error, E_USER_ERROR);

            #Save data or reports errors
            ($stmt_result = $stmt->get_result()) or trigger_error($stmt->error, E_USER_ERROR);

            #Check if are rows in query
            if ($stmt_result->num_rows > 0) {

                return true;

            } else {

                return false;
            }
            $stmt->close();

        }
        catch(Exception $e) {
            error_log($e->getMessage());
            exit('Error');
        }
    }


//    public function ajoutNouveauUsager($data){


    /**
     * @param $nom
     * @param $prenom
     * @param $mail
     * @param $password
     * @param $pseudo
     * @return mixed
     */
    public function ajoutNouveauUsager($nom,$prenom,$mail,$password,$pseudo){
//        if ($this->existUsager($data->mail,$data->pseudo) == false){
        if ($this->existUsager($mail,$pseudo) == false){
                //            $mdp = password_hash($data->mdp, PASSWORD_DEFAULT);
                $mdp = password_hash($password, PASSWORD_DEFAULT);
                $admin = '0';
                $stmt = $this->_db->prepare("INSERT INTO " .self::TABLE . " (nom, prenom, mail, mdp, admin,pseudo) VALUES (?, ?, ?,?,?,?)");
//            $stmt->bind_param("ssssis", $data->nom, $data->prenom, $data->mail,$mdp,$admin,$data->pseudo);
                $stmt->bind_param("ssssis", $nom, $prenom, $mail,$mdp,$admin,$pseudo);
//                $stmt->execute();
        }
        return $stmt->execute();
    }


    /**
     * @param $data
     * @return bool
     */
//    public function login($data){
    /**
     * @param $identifiant
     * @param $password
     * @return bool
     */
    public function login($identifiant,$password){
//        var_dump($identifiant);
//        var_dump($password);

            $error ='';
                $identifiantEscape = $this->_db->real_escape_string($identifiant);
//                $identifiant = $this->_db->real_escape_string($data->identifiant);

            $stmt = "SELECT * FROM " . self::TABLE. " WHERE mail = '".$identifiantEscape ."' OR pseudo ='" . $identifiantEscape ."'";
//            var_dump($stmt);
            $stmt_result = $this->_db->query($stmt);
//            var_dump($stmt_result->num_rows);

            if ($stmt_result->num_rows == 1) {
                $row_data = $stmt_result->fetch_assoc();
//                var_dump($row_data);
                if (password_verify($password, $row_data["mdp"])) {
//                if (password_verify($data->mdp, $row_data["mdp"])) {
                    $_SESSION['user_pseudo'] = $row_data["pseudo"];
                    $_SESSION['user_id'] = $row_data["id_usager"];

                    return true;
                }
            }

    }


}