package kissie;

import android.app.Activity;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.FrameLayout;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Iterator;
import java.util.Random;
import java.util.Timer;
import java.util.TimerTask;

import nadav.tasher.lightool.communication.OnFinish;
import nadav.tasher.lightool.communication.SessionStatus;
import nadav.tasher.lightool.communication.network.request.Post;
import nadav.tasher.lightool.communication.network.request.RequestParameter;
import nadav.tasher.lightool.graphics.views.appview.AppView;
import nadav.tasher.lightool.graphics.views.appview.navigation.Drag;
import nadav.tasher.lightool.info.Device;

public class Main extends Activity {
    static final int color = 0xff123bbf;
    static final String serverUrl = "http://url.com/kissiecount/action.php";
    private TextView avg, total, day;
    private boolean done = true;
    private String date;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        final AppView appView = new AppView(getApplicationContext(), getDrawable(R.drawable.ic_mushmush), 0x111111);
        appView.setBackgroundColor(color);
        appView.overlaySelf(getWindow());
        LinearLayout counter = new LinearLayout(getApplicationContext());
        counter.setLayoutParams(new FrameLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT));
        counter.setOrientation(LinearLayout.VERTICAL);
        counter.setGravity(Gravity.CENTER);
        TextView nt = new TextView(getApplicationContext());
        nt.setText("Well That Does Nothing");
        nt.setTextSize(34);
        nt.setGravity(Gravity.CENTER);
        appView.getDrag().setContent(nt);
        total = new TextView(getApplicationContext());
        day = new TextView(getApplicationContext());
        avg = new TextView(getApplicationContext());
        date = String.valueOf(Calendar.getInstance().get(Calendar.DAY_OF_YEAR) + 1);
        total.setGravity(Gravity.CENTER);
        avg.setGravity(Gravity.CENTER);
        day.setGravity(Gravity.CENTER);
        Button kiss = new Button(getApplicationContext());
        total.setTextSize(34);
        avg.setTextSize(32);
        day.setTextSize(30);
        kiss.setText("\uD83D\uDE19 \uD83D\uDE18 \uD83D\uDC8F");
        kiss.setGravity(Gravity.CENTER);
        kiss.setTextSize(40);
        kiss.setBackground(null);
//        kiss.setLayoutParams(new LinearLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT));
        kiss.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Toast.makeText(getApplicationContext(), "Updating Server", Toast.LENGTH_SHORT).show();
                new Post(serverUrl, new RequestParameter[]{new RequestParameter("add", date)}, null).execute();
            }
        });
        counter.addView(total);
        counter.addView(avg);
        counter.addView(day);
        counter.addView(kiss);
        appView.setContent(counter);
        setContentView(appView);
        startTimer();
    }

    private void startTimer() {
        Timer timer = new Timer();
        timer.scheduleAtFixedRate(new TimerTask() {
            public void run() {
                if (done) {
                    done = false;
                    if (Device.isOnline(getApplicationContext())) {
                        new Post(serverUrl, new RequestParameter[]{new RequestParameter("get", "all")}, new OnFinish() {
                            @Override
                            public void onFinish(SessionStatus sessionStatus) {
                                done = true;
                                if (sessionStatus.getStatus() == SessionStatus.FINISHED_SUCCESS) {
                                    try {
                                        update(new JSONObject(sessionStatus.getExtra()), date);
                                    } catch (JSONException ignored) {
                                    }
                                }

                            }
                        }).execute();
                    } else {
                        done = true;
                    }
                }
            }
        }, 0, 30 * 1000);
    }

    private void update(JSONObject datas, String date) {
        try {
            int totalNumber = 0;
            int dates = 0;
            double avrage;
            Iterator<String> keys = datas.keys();
            while (keys.hasNext()) {
                totalNumber += datas.getInt(keys.next());
                dates++;
            }
            if (dates != 0) {
                avrage = ((double)totalNumber / dates);
            }else {
                avrage = 0;
            }
            String totalS = "Total: " + totalNumber;
            total.setText(totalS);
            String averageS = "Average: " + String.valueOf(avrage) + " KS/D";
            avg.setText(averageS);
            if (datas.has(date)) {
                String dayS = "Day: " + datas.getInt(date);
                day.setText(dayS);
            } else {
                String dayS = "Day: 0";
                day.setText(dayS);
            }
        } catch (JSONException e) {

        }
    }
}
