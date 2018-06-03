<?php

/**
 * Created by PhpStorm.
 * User: sven
 */

namespace main\app\test\unit\classes;

use main\app\model\issue\IssueFilterModel;
use main\app\model\user\UserModel;
use main\app\classes\UserAuth;
use main\app\classes\UserLogic;

/**
 * 为IssueFavFilter逻辑类提供测试数据
 */
class IssueFavFilterDataProvider
{

    public static $insertIdArr = [];

    public static $insertUserIdArr = [];

    /**
     * 生成一条记录
     * @param array $info
     * @return array
     * @throws \Exception
     */
    public static function initFavFilter($info = [])
    {
        if (!isset($info['name'])) {
            $info['name'] = 'test-name-' . mt_rand(100, 999);
        }
        if (!isset($info['project_id'])) {
            $info['project_id'] = 0;
        }
        if (!isset($info['description'])) {
            $info['description'] = 'test-description';
        }
        if (!isset($info['author'])) {
            $info['author'] = 0;
        }
        if (!isset($info['share_obj'])) {
            $info['share_obj'] = '';
        }
        if (!isset($info['share_scope'])) {
            $info['share_scope'] = 'all';
        }
        if (!isset($info['filter'])) {
            $info['filter'] = '';
        }
        if (!isset($info['fav_count'])) {
            $info['fav_count'] = 0;
        }
        $model = new IssueFilterModel();
        list($ret, $insertId) = $model->insert($info);
        if (!$ret) {
            parent::fail(__CLASS__ . '/initFavFilter  failed,' . $insertId);
            return [];
        }
        self::$insertIdArr[] = $insertId;
        $row = $model->getRowById($insertId);
        return $row;
    }

    /**
     * 初始化用户
     */
    public static function initLoginUser()
    {
        $username = '190' . mt_rand(12345678, 92345678);

        // 表单数据 $post_data
        $postData = [];
        $postData['username'] = $username;
        $postData['phone'] = $username;
        $postData['email'] = $username . '@masterlab.org';
        $postData['display_name'] = $username;
        $postData['status'] = UserLogic::STATUS_OK;
        $postData['openid'] = UserAuth::createOpenid($username);

        $userModel = new UserModel();
        list($ret, $msg) = $userModel->insert($postData);
        if (!$ret) {
            var_dump(__CLASS__ . '/initUser  failed,' . $msg);
            return;
        }
        self::$insertUserIdArr[] = $msg;
        $user = $userModel->getRowById($msg);
        UserAuth::getInstance()->login($user);
        return $user;
    }


    /**
     * 清除测试数据
     */
    public static function clear()
    {
        if (!empty(self::$insertIdArr)) {
            $model = new IssueFilterModel();
            foreach (self::$insertIdArr as $id) {
                $model->deleteById($id);
            }
        }
        if (!empty(self::$insertUserIdArr)) {
            $model = new UserModel();
            foreach (self::$insertUserIdArr as $id) {
                $model->deleteById($id);
            }
        }
    }

}
