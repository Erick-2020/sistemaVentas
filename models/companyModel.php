<?php

    require_once "mainModel.php";

    class companyModel extends mainModel{
        protected static function dataCompanyModel(){
            $sql = mainModel::connection()->prepare("SELECT * FROM empresa");
            $sql->execute();

            return $sql;
        }

        protected static function addCompanyModel($data){
            $sql = mainModel::connection()->prepare("INSERT INTO empresa(empresa_nombre,
            empresa_email, empresa_telefono, empresa_direccion)
            VALUES(:NAMES, :EMAIL, :PHONE, :ADDRRESS)");

            $sql->bindParam(":NAMES", $data['NAME']);
            $sql->bindParam(":EMAIL", $data['EMAIL']);
            $sql->bindParam(":PHONE", $data['PHONE']);
            $sql->bindParam(":ADDRRESS", $data['ADDRESS']);
            $sql->execute();

            return $sql;
        }

        protected static function updateCompanyModel($dataArray){
            $sql = mainModel::connection()->prepare("UPDATE empresa SET empresa_nombre=:NAMES,
            empresa_email=:EMAIL, empresa_telefono=:PHONE, empresa_direccion=:ADDRESSS WHERE empresa_id =:ID");

            $sql->bindParam(":NAMES", $dataArray['NAME']);
            $sql->bindParam(":EMAIL", $dataArray['EMAIL']);
            $sql->bindParam(":PHONE", $dataArray['PHONE']);
            $sql->bindParam(":ADDRESSS", $dataArray['ADDRESS']);
            $sql->bindParam(":ID", $dataArray['ID']);
            $sql->execute();

            return $sql;
        }
    }