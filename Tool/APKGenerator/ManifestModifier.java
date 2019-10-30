import java.io.*;
import java.util.*;
import java.nio.file.Files;
class ManifestModifier
{
	public static ArrayList<String> readFiles()
	{
		ArrayList<String>activitylist=null;
		try
		{
			
			activitylist=new ArrayList();
			File folder = new File("../activities");
			File[] listOfFiles = folder.listFiles();

			for (int i = 0; i < listOfFiles.length; i++) 
			{
				  File file = listOfFiles[i];
				  if (file.isFile() && file.getName().endsWith(".ac")) 
				  {
					//String content = FileUtils.readFileToString(file);
					System.out.println(file.getName());
					String filepath="../activities/"+file.getName();
					FileReader fr=new FileReader(filepath);
					BufferedReader br=new BufferedReader(fr);
					String line=br.readLine();
					while(line!=null)
					{
						System.out.println(line);
						activitylist.add(line);
					    line=br.readLine();
					} 
				}
			}
		}
		catch(Exception e)
		{
			System.out.println(e.toString());
		}
		return activitylist;
	}
	public static void main(String[]args)
	{
		ArrayList<String>activitylist;

		int found=0;
		try
		{
			activitylist=readFiles();
			activitylist.add("</application>");
			//add permissions here
			activitylist.add("</manifest>");
			PrintStream ps=new PrintStream(new File("../AndroidManifest.xml"));

			FileReader fr=new FileReader("C:/Users/%username%/AndroidStudioProjects/RemoteHealthCareUseCase/app/src/main/AndroidManifest.xml");
			BufferedReader br=new BufferedReader(fr);
			String line=br.readLine();
			while(line!=null)
			{
				String line2=line.trim();
				if(line2.equalsIgnoreCase("</application>"))
				{
					found=1;
					break;
				}
				System.out.println(line);
				ps.println(line);
				line=br.readLine();
			}
			if(found==1)
			{
				System.out.println("Hey I got the point");
				for(String s:activitylist)
				{
					ps.println(s);
				}
			}
			ps.close();
			br.close();
			fr.close();
			String filePath ="../bats/copymanifest.bat";
			Process p = Runtime.getRuntime().exec(filePath);
			Thread.sleep(1000);
			
			filePath ="../bats/copyxml.bat";
			 p = Runtime.getRuntime().exec(filePath);
			Thread.sleep(1000);
			
			filePath ="../bats/copyjava.bat";
			 p = Runtime.getRuntime().exec(filePath);
			Thread.sleep(1000);
			

		}
		catch(Exception e)
		{
			System.out.println(e.toString());
		}
	}
}