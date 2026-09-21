package com.example.toxampp

import android.os.AsyncTask
import android.os.Bundle
import android.view.View
import android.widget.Button
import android.widget.EditText
import android.widget.Toast
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import java.io.BufferedWriter
import java.io.OutputStreamWriter
import java.net.HttpURLConnection
import java.net.URL
import java.net.URLEncoder


class MainActivity : AppCompatActivity() {
    lateinit var username: EditText
    lateinit var pass: EditText
    lateinit var btn_register: Button

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_main)

        username = findViewById(R.id.ed_user)
        pass = findViewById(R.id.ed_password)
        btn_register = findViewById(R.id.btn_reg)

        btn_register.setOnClickListener {
            val username = username.text.toString()
            val password = pass.text.toString()

            if (username.isNotEmpty() && password.isNotEmpty()) {
                RegisterUserTask().execute(username, password)
            } else {
                Toast.makeText(this, "Please fill all fields", Toast.LENGTH_SHORT).show()
            }
        }


        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }
    }
    inner class RegisterUserTask : AsyncTask<String, Void, String>() {
        override fun doInBackground(vararg params: String): String {
            val username = params[0]
            val password = params[1]
            var result: String

            try {
                val url = URL("http://10.0.2.2/android_api/register.php") // 10.0.2.2 is localhost for Android Emulator
                val conn = url.openConnection() as HttpURLConnection
                conn.requestMethod = "POST"
                conn.doOutput = true

                val outputStream = conn.outputStream
                val writer = BufferedWriter(OutputStreamWriter(outputStream, "UTF-8"))
                val data = URLEncoder.encode("username", "UTF-8") + "=" + URLEncoder.encode(username, "UTF-8") + "&" +
                        URLEncoder.encode("password", "UTF-8") + "=" + URLEncoder.encode(password, "UTF-8")
                writer.write(data)
                writer.flush()
                writer.close()
                outputStream.close()

                result = if (conn.responseCode == HttpURLConnection.HTTP_OK) {
                    "Registration Successful"
                } else {
                    "Error in registration"
                }
                conn.disconnect()
            } catch (e: Exception) {
                e.printStackTrace()
                result = e.message ?: "An error occurred"
            }

            return result
        }

        override fun onPostExecute(result: String) {
            Toast.makeText(this@MainActivity, result, Toast.LENGTH_SHORT).show()
        }
    }
}