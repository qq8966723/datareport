<?php
/**
 * description: Realtime Data Process
 * @author         tux (8966723@qq.com)
 * @date        2019-12-02 14:21:34
 * @version     [1.0]
 * @copyright    firestorm phper
 */

namespace Cashcash\DataReport;

class RealtimeProcess
{
    private $baseUrl;

    /**
     * [__construct description]
     * @author tux (8966723@qq.com) 2019-12-10
     * @param  integer $projectEnv [description]
     */
    public function __construct($projectEnv)
    {
        if ($projectEnv == 0) {
            $this->baseUrl = 'http://teststat.toolsvqdr.com/api/';
        } else {
            $this->baseUrl = 'http://stat-sg.toolsvqdr.com/api/';
        }
    }

    /**
     * sendOut 调用计费服务器各上报接口
     * @param $url
     * @param $data
     * @return mixed
     * @throws \Error
     * @throws \Exception
     */
    public function sendOut($url, $data)
    {
        $url = $this->baseUrl . $url;
        try {
            return $this->doPost($url, json_encode($data));
        } catch (\Exception $ex) {
            throw $ex;
        } catch (\Error $ex) {
            throw $ex;
        }
    }
    /*
    get
     */
    private function doGet($url, $headers = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 不从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 5000);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        return $output;
    }
    /*
    post
     */
    private function doPost($url, $data, $headers = array())
    {
        // \Yii::error('tux---'.$post_data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // 执行后不直接打印出来
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 设置请求方式为post
        curl_setopt($ch, CURLOPT_POST, true);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // 请求头，可以传数组
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 不从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 5000);
        $output = curl_exec($ch);
        $error  = curl_error($ch);
        $info   = curl_getinfo($ch);
        curl_close($ch);
        if ($error || $info['http_code'] != 200) {
            if ($error) {
                throw new \Exception($error . ' 上报数据为：' . json_encode($data));
            }
            throw new \Exception('curl request failed ' . ' 上报数据为：' . json_encode($data));
        }
        return $output;
    }
}
