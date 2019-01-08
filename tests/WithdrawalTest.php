<?php

namespace KuCoin\SDK\Tests;

use KuCoin\SDK\Auth;
use KuCoin\SDK\PrivateApi\Withdrawal;

class WithdrawalTest extends TestCase
{
    public function testNewAuth()
    {
        $auth = new Auth($this->apiKey, $this->apiSecret, $this->apiPassPhrase);
        $this->assertInstanceOf(Auth::class, $auth);
        return $auth;
    }

    /**
     * @depends testNewAuth
     * @param Auth $auth
     * @return Withdrawal
     */
    public function testNewWithdrawal(Auth $auth)
    {
        $api = new Withdrawal($auth);
        $this->assertInstanceOf(Withdrawal::class, $api);
        return $api;
    }

    /**
     * @depends testNewWithdrawal
     * @param Withdrawal $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetQuotas(Withdrawal $api)
    {
        $data = $api->getQuotas('BTC');
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('limitBTCAmount', $data);
        $this->assertArrayHasKey('withdrawMinFee', $data);
        $this->assertArrayHasKey('innerWithdrawMinFee', $data);
        $this->assertArrayHasKey('availableAmount', $data);
        $this->assertArrayHasKey('remainAmount', $data);
//        $this->assertArrayHasKey('usedAmount', $data);
        $this->assertArrayHasKey('precision', $data);
//        $this->assertArrayHasKey('limitAmount', $data);
        $this->assertArrayHasKey('currency', $data);
        $this->assertArrayHasKey('isWithdrawEnabled', $data);
        $this->assertArrayHasKey('withdrawMinSize', $data);
    }

    /**
     * @depends testNewWithdrawal
     * @param Withdrawal $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testApply(Withdrawal $api)
    {
        $params = [
            'currency' => 'BTC',
            'address'  => '1BcTdvq6Qdh7GnviHTYHq4tBvU32FfUbGz',
            'amount'   => 0.3,
            'remark'   => 'test apply withdrawal',
        ];
        $data = $api->apply($params);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('withdrawId', $data);
    }


    /**
     * @depends testNewWithdrawal
     * @param Withdrawal $api
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetList(Withdrawal $api)
    {
        $params = [
            'currency' => 'BTC',
        ];
        $pagination = [
            'pageNum'  => 1,
            'pageSize' => 5,
        ];
        $data = $api->getList($params, $pagination);
        $this->assertPagination($data);
        foreach ($data['items'] as $item) {
            $this->assertInternalType('array', $item);
            $this->assertArrayHasKey('address', $item);
            $this->assertArrayHasKey('amount', $item);
            $this->assertArrayHasKey('createdAt', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('currencyName', $item);
            $this->assertArrayHasKey('fee', $item);
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('isInner', $item);
            $this->assertArrayHasKey('remark', $item);
            $this->assertArrayHasKey('status', $item);
            $this->assertArrayHasKey('updatedAt', $item);
            $this->assertArrayHasKey('walletTxId', $item);
        }
        var_dump($data['items']);
        return $data['items'];
    }

    /**
     * @depends testNewWithdrawal
     * @depends testGetList
     * @param Withdrawal $api
     * @param array $withdraws
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetDetail(Withdrawal $api, array $withdraws)
    {
        if (isset($withdraws[0])) {
            $item = $api->getDetail($withdraws[0]['id']);
            var_dump($item);
            $this->assertInternalType('array', $item);
            $this->assertArrayHasKey('address', $item);
            $this->assertArrayHasKey('amount', $item);
            $this->assertArrayHasKey('createdAt', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('currencyName', $item);
            $this->assertArrayHasKey('fee', $item);
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('isInner', $item);
            $this->assertArrayHasKey('remark', $item);
            $this->assertArrayHasKey('status', $item);
            $this->assertArrayHasKey('updatedAt', $item);
            $this->assertArrayHasKey('walletTxId', $item);
        }
    }

    /**
     * @depends testNewWithdrawal
     * @param Withdrawal $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCancel(Withdrawal $api)
    {
        $data = $api->cancel('5c1cb7bb03aa6774239b772c');
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('cancelledWithdrawIds', $data);
        var_dump($data);
    }

    /**
     * @depends testNewWithdrawal
     * @param Withdrawal $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCancelMany(Withdrawal $api)
    {
        $params = [
            'currency' => 'BTC',
        ];
        $data = $api->cancelMany($params);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('cancelledWithdrawIds', $data);
        var_dump($data);
    }
}