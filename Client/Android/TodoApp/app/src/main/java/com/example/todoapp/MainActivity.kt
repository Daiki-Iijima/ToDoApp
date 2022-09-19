package com.example.todoapp

import android.content.Context
import android.os.Bundle
import android.os.Handler
import android.os.Looper
import android.util.Log
import android.widget.ArrayAdapter
import android.widget.Button
import android.widget.ListView
import androidx.appcompat.app.AppCompatActivity
import com.fasterxml.jackson.databind.ObjectMapper
import com.fasterxml.jackson.module.kotlin.readValue
import com.fasterxml.jackson.module.kotlin.registerKotlinModule
import java.io.IOException

class MainActivity : AppCompatActivity() {

    private lateinit var taskListView : ListView

    private lateinit var mainThreadHandler: Handler

    private lateinit var context: Context

    private val getUrl = "http://192.168.50.249:8888/todo/all"

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        mainThreadHandler = Handler(Looper.getMainLooper())

        taskListView = findViewById(R.id.taskListView)

        context = this

        val httpTransport = HttpTransport()
        //  リクエスト結果の取得
        httpTransport.responseListener = object : OnResponseListener {
            override fun onSuccess(response: String) {

                //  Jsonをパース
                val mapper = ObjectMapper().registerKotlinModule()
                val todoList = mapper.readValue<List<Todo>>(response)

                //  リストビューに反映
                runOnUiThread {

                    val titleArray: Array<String> = Array(todoList.count()) { "" }

                    var count = 0
                    todoList.forEach {
                        titleArray[count] = it.title
                        count += 1
                    }

                    taskListView.adapter = ArrayAdapter(
                        context,
                        android.R.layout.simple_list_item_1,
                        titleArray
                    )
                }
            }

            override fun onFailure(error: IOException) {
                Log.e("Error",error.message.toString())
            }
        }

        val getRequestBtn = findViewById<Button>(R.id.getRequestBtn)

        //  ボタンを押した時に、Getリクエストを実行する
        getRequestBtn.setOnClickListener {
            httpTransport.getRequest(getUrl)
        }

    }
}