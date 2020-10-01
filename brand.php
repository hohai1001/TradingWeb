<?php
require_once APP_PATH . '/app/config/controller.php';
class Brand extends Controller{
    public function index(){
        //echo $_SESSION["user"]["group"]; die();
        if(isset($_SESSION["user"]["id"])){
            if($_SESSION["user"]["group"] != 1){
                header("Location:" . "/");
            }
        }
        else{
            header("Location:" . "/admin/login");
            die();
        }

        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "POST"){
            $isSuccess = false;
            $brandName = $_POST["brandName"];  
            if(isset($_POST["brandId"])){
                //update
                $brandId = $_POST["brandId"];
                $result = $this->db->updateBrand($brandId, $brandName);
                if($result){
                    if($_FILES["brandImg"]["tmp_name"]){
                        $saveFilePath = APP_PATH . "/public/images/brand/" . $brandId . ".png";
                        if(move_uploaded_file($_FILES["brandImg"]["tmp_name"], $saveFilePath)){
                            $isSuccess = true;
                        }
                    }
                    else{
                        $isSuccess = true;

                    }
                }
            }
            else{
                // insert
                $idBrand = $this->db->createBrand($brandName);
                if($idBrand){
                    $saveFilePath = APP_PATH . "/public/images/brand/" . $idBrand . ".png";
                    if(move_uploaded_file($_FILES["brandImg"]["tmp_name"], $saveFilePath)){
                        $isSuccess = true;
                        // $_SESSION["reloadBrand"] = $isSuccess;
                        // header("Location:" . "/admin/thuong-hieu");
                        // die();
                    }
                }
            }
            if($isSuccess){
                $_SESSION["reloadBrand"] = $isSuccess;
                header("Location:" . "/admin/thuong-hieu");
                die();
            }
        }

        // load list brand
        $lstBrands = $this->db->loadListBrands();

        if(isset($_SESSION["reloadBrand"])){
            $this->view->isSuccess = $_SESSION["reloadBrand"];
            unset($_SESSION["reloadBrand"]);
        }
        $this->view->lstBrands = $lstBrands;

        $this->view->render("brand/index");
    }
}