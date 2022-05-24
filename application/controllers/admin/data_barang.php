<?php
defined('BASEPATH') or exit('No direct script aceess allowed');

class Data_barang extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if ($this->session->userdata('role_id') != '1') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert"> Anda Belum Login! <button type="button" class="close" data-dismiss="alert" arial-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            redirect('auth');
        }
    }

    public function index()
    {

        $data['barang'] = $this->Barang_model->tampil_data()->result();
        $this->load->view('admin/data_barang', $data);
    }

    public function tambah_aksi()
    {
        $nama_brg   = $this->input->post('nama_brg');
        $keterangan_short = $this->input->post('keterangan_short');
        $kategori   = $this->input->post('kategori');
        $harga      = $this->input->post('harga');
        $stok       = $this->input->post('stok');
        $gambar     = $_FILES['gambar']['name'];
        // if ($gambar = '') {'


        //     '
        // } else {
        //     $config['upload_path'] = './assets/img/home/';
        //     $config['allowed_types'] = 'jpg|jpeg|png|gif';

        //     $this->load->library('upload', $config);

        //     if (!$this->upload->do_upload('gambar')) {
        //         echo "Gambar Gagal diupload!";
        //     } else {
        //         $gambar = $this->upload->data('file_name');
        //     }
        // }

        if ($gambar) {
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['upload_path'] = './assets/img/home/';
            $config['file_name'] = $_FILES['gambar']['name'];

            $this->load->library('upload', $config);
            // $this->upload->initialize($config);

            if ($this->upload->do_upload('gambar')) {
                $upload_image = $this->upload->data('file_name');
                $this->db->set('gambar', $upload_image);
            } else {
                redirect('admin/data_barang');
            }
        }

        $data = array(
            'nama_brg'          => $nama_brg,
            'keterangan_short'    => $keterangan_short,
            'kategori'          => $kategori,
            'harga'             => $harga,
            'stok'              => $stok,
            'gambar' => $gambar,
        );

        $this->Barang_model->tambah_barang($data, 'tb_barang');
        redirect('admin/data_barang/index');
    }

    public function edit($id)
    {
        $where = array('id_brg' => $id);
        $data['barang'] = $this->Barang_model->edit_barang($where, 'tb_barang')->result();
        $this->load->view('admin/edit_barang', $data);
    }

    public function update()
    {
        $id = $this->input->post('id_brg');
        $nama_brg = $this->input->post('nama_brg');
        $keterangan_short = $this->input->post('keterangan_short');
        $kategori = $this->input->post('kategori');
        $harga = $this->input->post('harga');
        $stok = $this->input->post('stok');

        $data = array(
            'nama_brg' => $nama_brg,
            'keterangan_short' => $keterangan_short,
            'kategori' => $kategori,
            'harga' => $harga,
            'stok' => $stok
        );

        $where = array(
            'id_brg' => $id
        );

        $this->Barang_model->update_data($where, $data, 'tb_barang');
        redirect('admin/data_barang/index');
    }

    public function hapus($id)
    {
        $where = array('id_brg' => $id);
        $this->Barang_model->hapus_data($where, 'tb_barang');
        redirect('admin/data_barang');
    }
}
