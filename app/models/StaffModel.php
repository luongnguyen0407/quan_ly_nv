<?php
class StaffModel extends DB
{
    use LoopData;
    public function createNewStaff($data)
    {
        $sql = "INSERT INTO `tb_nhanvien`(`ho_ten`, `gioi_tinh`, `dia_chi`, `so_dien_thoai`,`email`, `ngay_sinh`, `can_cuoc`, `hinh_anh`, `maCV`, `maPB`, `maTD`) 
        VALUES ('" . $data['name'] . "','" . $data['gender'] . "','" .
            $data['dia_chi'] . "','" . $data['phone'] . "','" . $data['email'] . "','" . $data['sinh_nhat'] . "','" .
            $data['can_cuoc'] . "','" . $data['img'] . "','" .
            $data['position'] . "','" . $data['department'] . "','" . $data['trinh_do'] . "')";
        try {
            //code...
            $this->link->query($sql);
            $id = $this->link->insert_id;
            $password = password_hash($data['can_cuoc'], PASSWORD_DEFAULT);
            //add hop dong
            $insertHD = "INSERT INTO `tb_hopdong`( `so_hop_dong`, `ngay_bat_dau`, `ngay_ket_thuc`, `luong_cung`, `chi_tiet`, `maNV`) 
            VALUES ('" . $data['hop_dong_id'] . "','" . $data['date_start'] . "','" . $data['date_end'] . "','" . $data['salary'] . "','null','" . $id . "')";
            //add acc
            $insertAcc = "INSERT INTO `tb_taikhoan`(`tai_khoan`, `mat_khau`, `chuc_vu`, `maNV`) 
            VALUES ('" . $data['can_cuoc'] . "','" . $password . "','0', '" . $id . "')";
            if ($this->link->query($insertHD) && $this->link->query($insertAcc)) {
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            // throw $th;
            return false;
        }
    }
    public function getDetailStaff($maNV)
    {
        $sql = "SELECT tb_nhanvien.ho_ten,  tb_nhanvien.hinh_anh, tb_chucvu.ten_chuc_vu
            FROM tb_nhanvien
            INNER JOIN tb_chucvu ON tb_nhanvien.maCV = tb_chucvu.maCV WHERE maNV = $maNV;";
        $kq = $this->link->query($sql)->fetch_assoc();
        if ($kq)
            return $kq;
        return false;
    }

    public function updateStaff($newValue, $oldValue)
    {
        $sql = "UPDATE `tb_nhanvien` SET `ho_ten`='" . $newValue['name'] . "',
        `gioi_tinh`='" . $newValue['gender'] . "',`dia_chi`= '" . $newValue['dia_chi'] . "',`so_dien_thoai`='" . $newValue['phone'] . "'
        ,`email`='" . $newValue['email'] . "',`ngay_sinh`='" . $newValue['sinh_nhat'] . "',`can_cuoc`='" . $newValue['can_cuoc'] . "',
        `hinh_anh`='" . $newValue['img'] . "',`maCV`='" . $newValue['position'] . "',`maPB`='" . $newValue['department'] . "',`maTD`= '" . $newValue['trinh_do'] . "' WHERE maNV = '" . $oldValue['maNV'] . "'";
        $kq = $this->link->query($sql);
        if (!$kq) return false;
        if ($newValue['can_cuoc'] != $oldValue['can_cuoc'] || $newValue['hop_dong_id'] != $oldValue['so_hop_dong'] || $newValue['date_start'] != $oldValue['ngay_bat_dau'] || $newValue['date_end'] != $oldValue['ngay_ket_thuc'] || $newValue['salary'] != $oldValue['luong_cung']) {
            $updateHD = "UPDATE `tb_hopdong` SET `so_hop_dong`='" . $newValue['hop_dong_id'] . "',`ngay_bat_dau`='" . $newValue['date_start'] . "',`ngay_ket_thuc`='" . $newValue['date_end'] . "',`luong_cung`='" . $newValue['salary'] . "' WHERE maNV= '" . $oldValue['maNV'] . "' AND maHD= '" . $oldValue['maHD'] . "'";
            $res = $this->link->query($updateHD);
            if ($res) {
                $updateAcc = "UPDATE `tb_taikhoan` SET `tai_khoan`='" . $newValue['can_cuoc'] . "' WHERE maNV = '" . $oldValue['maNV'] . "'";
                $res = $this->link->query($updateAcc);
                if ($res) return true;
                return false;
            }
        } else {
            return true;
        }
    }

    public function getStaffExcel()
    {
        $sql = "SELECT tb_nhanvien.ho_ten, tb_nhanvien.dia_chi,tb_nhanvien.ngay_sinh,tb_nhanvien.so_dien_thoai,tb_nhanvien.can_cuoc, tb_nhanvien.hinh_anh,tb_hopdong.so_hop_dong, tb_hopdong.ngay_bat_dau, tb_hopdong.ngay_ket_thuc, tb_phongban.ten_phong FROM tb_hopdong INNER JOIN tb_nhanvien ON tb_hopdong.maNV=tb_nhanvien.maNV INNER JOIN tb_phongban ON tb_nhanvien.maPB=tb_phongban.maPB";
        $kq = $this->link->query($sql);
        if ($kq) return $kq;
    }
    public function getAllStaff()
    {
        $sql = "SELECT tb_nhanvien.maNV, tb_nhanvien.ho_ten, tb_nhanvien.dia_chi,tb_nhanvien.ngay_sinh,tb_nhanvien.so_dien_thoai,tb_nhanvien.can_cuoc, tb_nhanvien.hinh_anh, tb_nhanvien.gioi_tinh,tb_hopdong.so_hop_dong, tb_hopdong.ngay_bat_dau, tb_hopdong.ngay_ket_thuc, tb_phongban.ten_phong FROM tb_hopdong INNER JOIN tb_nhanvien ON tb_hopdong.maNV=tb_nhanvien.maNV INNER JOIN tb_phongban ON tb_nhanvien.maPB=tb_phongban.maPB";
        $kq = $this->link->query($sql);
        if ($kq) return $kq;
    }
    public function addNewStaffExcel($data)
    {
        if (empty($data)) return;
        $fieldError = array();
        foreach ($data as $row) {
            // PrintDisplay::printFix($row);
            $checkValue = $this->ValidateDataExcel($row);
            if (is_array($checkValue)) {
                $res = $this->createNewStaff($checkValue);
                if (!$res) {
                    continue;
                }
            } else {
                $fieldError[] =  $row[0] . ':' . $checkValue;
                // continue;
            }
        }
        return $fieldError;
    }

    public function findData($name, $data, $wh = 0)
    {
        $name = mysqli_real_escape_string($this->link, $name);
        $data = mysqli_real_escape_string($this->link, $data);
        if (empty($wh)) {
            $sql = "SELECT $name FROM `tb_nhanvien` WHERE $name = '" . $data . "'";
            $kq = $this->link->query($sql);
            if (mysqli_num_rows($kq)) {
                return true;
            } else {
                return false;
            }
        } else {
            $sql = "SELECT $wh FROM `tb_nhanvien` INNER JOIN tb_hopdong ON tb_nhanvien.maNV = tb_hopdong.maNV WHERE tb_nhanvien.$name = '" . $data . "'";
            $kq = $this->link->query($sql);
            return $kq;
        }
    }

    public function updateData($table, $what, $new, $where, $whereid)
    {
        $sql = "UPDATE `$table` SET `$what`='$new' WHERE $where = '" . $whereid . "'";
        $kq = $this->link->query($sql);
        if ($kq) return true;
        return false;
    }

    public function getContract($uId)
    {
        $sql = "SELECT `luong_cung` FROM `tb_hopdong` WHERE maNV = $uId";
        $kq =  $this->link->query($sql);
        if ($kq) {
            return $kq->fetch_assoc();
        } else {
            http_response_code(401);
        }
    }

    public function getAllSalary($year, $month)
    {
        $sql = "SELECT (SELECT COUNT(maNN) as holiday FROM `tb_ngaynghi` WHERE YEAR(ngay_nghi) = '" . $year . "' AND MONTH(ngay_nghi) = '" . $month . "') AS holiday, 
        (SELECT SUM(( HOUR(gio_ra) - HOUR(gio_vao)) * 60 + MINUTE(gio_ra) - MINUTE(gio_vao)) as totalMin FROM tb_bangcong WHERE tb_nhanvien.maNV = tb_bangcong.maNV AND gio_vao IS NOT NULL AND gio_ra IS NOT NULL AND YEAR(ngay_cham) = $year AND MONTH(ngay_cham) = $month GROUP BY tb_bangcong.maNV) as min
        ,tb_nhanvien.maNV, tb_nhanvien.ho_ten, tb_nhanvien.hinh_anh, tb_nhanvien.gioi_tinh, tb_hopdong.luong_cung FROM tb_hopdong INNER JOIN tb_nhanvien ON tb_hopdong.maNV=tb_nhanvien.maNV;";
        $kq =  $this->link->query($sql);
        if ($kq) {
            return $this->returnArray($kq);
        } else {
            return false;
        }
    }
    public function delStaff($id)
    {
        $sql = "DELETE FROM `tb_nhanvien` WHERE maNV = '$id'";
        $kq =  $this->link->query($sql);
        if ($kq) return true;
        return false;
    }
}
