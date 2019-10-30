
import java.io.*;
import java.nio.file.Files;

class CommandEvaluation
{
	public static void main(String[]args)
	{
		try
		{
			//String []command={"cmd /c mkdir souvikdir"};
			String cmd="android create project -a RemoteHealthcareTestCase -k com.example.username.RemoteHealthcareTestCase -t 1 -p RemoteHealthcareTestCase -g -v 1.1.0";
			//String cpy="xcopy /E /A E:/an/AcceleoToAndroid1 E:/an/dest1"

			Process p=Runtime.getRuntime().exec("cmd /c "+cmd);
			Thread.sleep(80000);
			
		}
		catch(Exception e)
		{
			System.out.println(e.toString());
		}
		

	}
}