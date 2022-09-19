package com.example.todoapp

import okhttp3.*
import java.io.IOException
import java.util.concurrent.TimeUnit

interface OnResponseListener{
    //  通信成功
    fun onSuccess(response:String)

    //  通信失敗
    fun onFailure(error:IOException)
}

class HttpTransport {

    var responseListener: OnResponseListener? = null

    // HTTP Getリクエストを行う
    fun getRequest(url:String){

        // OkHttpClientを作成
        val client = OkHttpClient.Builder()
            .connectTimeout(5000, TimeUnit.MILLISECONDS)    //  接続タイムアウト
            .readTimeout(5000, TimeUnit.MILLISECONDS)       //  読み込みタイムアウト
            .build()

        // Requestを作成
        val request = Request.Builder()
            .url(url)
            .build()

        client.newCall(request).enqueue(object : Callback {
            override fun onResponse(call: Call, response: Response) {
                // Responseの読み出し
                val responseBody = response.body?.string().orEmpty()

                responseListener?.onSuccess(responseBody)
            }

            override fun onFailure(call: Call, e: IOException) {
                responseListener?.onFailure(e)
            }
        })
    }
}