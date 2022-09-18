package com.example.todoapp

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.os.Handler
import android.os.Looper
import android.util.Log
import android.view.View
import android.widget.Button
import android.widget.TextView
import androidx.activity.viewModels
import androidx.lifecycle.Observer
import okhttp3.*
import java.io.BufferedReader
import java.io.IOException
import java.io.InputStream
import java.io.InputStreamReader
import java.net.HttpURLConnection
import java.net.URL
import java.util.concurrent.TimeUnit

const val GET_URL = "http://192.168.50.249:8888/todo/all"

class MainActivity : AppCompatActivity() {

    private lateinit var getResultTextView : TextView

    private lateinit var mainThreadHandler: Handler

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        mainThreadHandler = Handler(Looper.getMainLooper())

        getResultTextView = findViewById<TextView>(R.id.getResultText)

        val getRequestBtn = findViewById<Button>(R.id.getRequestBtn)
        getRequestBtn.setOnClickListener(OnClickGetRequestListener(getResultTextView,mainThreadHandler))

    }


    class OnClickGetRequestListener constructor(private val showTextView:TextView,
                                                private val handler:Handler) : View.OnClickListener{
        override fun onClick(view: View?) {
            startGetRequest()
        }

        private fun startGetRequest(){
            // OkHttpClientを作成
            val client = OkHttpClient.Builder()
                .connectTimeout(1000, TimeUnit.MILLISECONDS)
                .readTimeout(1000, TimeUnit.MILLISECONDS)
                .build()

            // Requestを作成
            val request = Request.Builder()
                .url(GET_URL)
                .build()

            client.newCall(request).enqueue(object : Callback {
                override fun onResponse(call: Call, response: Response) {
                    // Responseの読み出し
                    val responseBody = response.body?.string().orEmpty()
                    // 必要に応じてCallback
                    Log.d("result",responseBody.toString())

                    // Handlerを使用してメイン(UI)スレッドに処理を依頼する
                        handler.post {
                            showTextView.text = responseBody.toString()
                        };

                }

                override fun onFailure(call: Call, e: IOException) {
                    Log.e("Error", e.toString())
                    // 必要に応じてCallback
                }
            })
        }
    }
}