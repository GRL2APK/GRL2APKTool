package AcceleoGoal2Code.main.services;
import java.sql.*;
import java.io.*;
import java.util.*;
import java.util.concurrent.*;
import AcceleoGoal2Code.main.GoalClass;
import AcceleoGoal2Code.main.GoalNFR;
public class InsertInDB 
{
	static Connection con=null;
	static Statement stmt=null;
	static ResultSet rs=null;
	static CopyOnWriteArrayList<GoalClass> freshGoalList;
	public static void insertDB(int parse1or2)
	{
		try
		{
			String actor="";
			//actor parsing
			FileReader fr=new FileReader("../AcceleoGoal2Code/target/actor.txt");
			BufferedReader br=new BufferedReader(fr);
			String line=br.readLine();
			while(line!=null)
			{
				line=line.trim();
				StringTokenizer st=new StringTokenizer(line);
				while(st.hasMoreTokens())
				{
					String token=st.nextToken();
					if(token.equalsIgnoreCase("Actor:"))
					{
						actor=st.nextToken();
					}
				}
				line=br.readLine();
			}
			//end of actor parsing
			
			String drivername="com.microsoft.sqlserver.jdbc.SQLServerDriver";
			Class.forName(drivername);
			String db="jdbc:sqlserver://localhost:1433;user=sa;password=cmsa019;databaseName=GRL2APK";
			con=DriverManager.getConnection(db);
			//System.out.println("Driver loaded successfully");
	        stmt=con.createStatement();
	        //stmt.executeUpdate("insert into fsmgoalData values(10,)")
	        if(parse1or2==1)
	        {
	        	for(GoalClass g: freshGoalList)
	        	{
	        		stmt.executeUpdate("insert into fsmdata2(goal,parent,timeorder,decomtype,demands,status,actor)values('"+g.getGoal()+"','none',0,'null','none',0,'"+g.getActor()+"')");
	        	}
	        }
	        else if(parse1or2==2)
	        {
	        	for(GoalClass g:freshGoalList)
	        	{
	        		stmt.executeUpdate("update fsmdata2 set parent='"+g.getParent()+"',decomtype='"+g.getDecomType()+"' where goal='"+g.getGoal()+"'");
	        	}
	        }
	        con.close();
	        stmt.close();
		}
		catch(Exception e)
		{
			System.err.println(e.toString());
		}
	}
	public static void parse()
	{
		CopyOnWriteArrayList<GoalClass>goallist=new CopyOnWriteArrayList();
		
		String goal=" ",parent=" ",decomType=" ";
		try {
			FileReader fr=new FileReader("../AcceleoGoal2Code/target/decom.txt");
			BufferedReader br=new BufferedReader(fr);
			String line=br.readLine();
			while(line!=null)
			{
				StringTokenizer st=new StringTokenizer(line);
				
				while(st.hasMoreTokens())
				{
					String token=st.nextToken();
					//System.out.println(token);
					if(token.equalsIgnoreCase("Goal:"))
					{
						goal=st.nextToken();
					}
					if(token.equalsIgnoreCase("Parent:"))
					{
						parent=st.nextToken();
					}
					if(token.equalsIgnoreCase("DecomType:"))
					{
						decomType=st.nextToken();
					}
				}
				
						GoalClass g=new GoalClass();
						g.setGoal(goal);
						g.setParent(parent);
						g.setDecomType(decomType);
						goallist.add(g);
						line=br.readLine();
					
				
			}
			
			for(GoalClass g:goallist)
			{
				System.out.println("Goal: "+g.getGoal()+" Parent: "+g.getParent());
			}
			removeDuplicate(goallist);
			insertDB(2);
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		
	}
	
	public static void parse2()
	{
		CopyOnWriteArrayList<GoalClass>goallist1=new CopyOnWriteArrayList();
		
		String goal=" ",parent=" ",actor=" ";
		try {
			FileReader fr=new FileReader("../AcceleoGoal2Code/target/goal.txt");
			BufferedReader br=new BufferedReader(fr);
			String line=br.readLine();
			while(line!=null)
			{
				StringTokenizer st=new StringTokenizer(line);
				
				while(st.hasMoreTokens())
				{
					String token=st.nextToken();
					//System.out.println(token);
					if(token.equalsIgnoreCase("Goal:"))
					{
						goal=st.nextToken();
					}
					if(token.equalsIgnoreCase("Parent:"))
					{
						parent=st.nextToken();
					}
					if(token.equalsIgnoreCase("Actor:"))
					{
						actor=st.nextToken();
					}
				}
				
						GoalClass g=new GoalClass();
						g.setGoal(goal);
						g.setParent(parent);
						g.setActor(actor);
						goallist1.add(g);
						line=br.readLine();
					
				
			}
			
			for(GoalClass g:goallist1)
			{
				System.out.println("Goal: "+g.getGoal()+" Parent: "+g.getParent());
			}
			removeDuplicate(goallist1);
			insertDB(1);
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
	}
	public static void removeDuplicate(CopyOnWriteArrayList<GoalClass>goals)
	{
		int ismatched=0; 
		freshGoalList=new CopyOnWriteArrayList<GoalClass>();
		for(GoalClass g:goals)
		{
			ismatched=0;
			//System.out.println("For "+g.getGoal());
			for(GoalClass g2:freshGoalList)
			{
				if(g.getGoal().equalsIgnoreCase(g2.getGoal()))
				{
					ismatched=1;
				}
				
			}
			if(ismatched==0)
			{
				freshGoalList.add(g);
			}
		}
		System.out.println("\nAfter Cleaned up\n");
		for(GoalClass g:freshGoalList)
		{
			System.out.println("Goal: "+g.getGoal()+" Parent: "+g.getParent());
		}
	}
	
	public static void updateDemands()
	{
		HashMap<String,String>goalDemand=new HashMap<String, String>();
		//ArrayList<GoalNFR>goalDemand=new ArrayList<>();
		try
		{
			String drivername="com.microsoft.sqlserver.jdbc.SQLServerDriver";
			Class.forName(drivername);
			String db="jdbc:sqlserver://localhost:1433;user=sa;password=cmsa019;databaseName=GRL2APK";
			con=DriverManager.getConnection(db);
			//System.out.println("Driver loaded successfully");
	        stmt=con.createStatement();
			FileReader fr=new FileReader("../AcceleoGoal2Code/target/goaldemand.txt");
			BufferedReader br=new BufferedReader(fr);
			String line=br.readLine();
			String goalname="",nfrname="";
			while(line!=null)
			{
				line=line.trim();
				StringTokenizer st=new StringTokenizer(line);
				while(st.hasMoreTokens())
				{
					String token=st.nextToken();
					if(token.equalsIgnoreCase("Goal:"))
					{
						goalname=st.nextToken().trim();
						System.out.println("Hii "+goalname);
					}
					if(token.equalsIgnoreCase("demands"))
					{
						nfrname=st.nextToken();
					}
					
				}
				if(goalname!=null && nfrname!=null)
				{
					GoalNFR goalnfr=new GoalNFR();
//					goalnfr.setGoal(goalname);
//					goalnfr.setNfr(nfrname);
//					goalDemand.add(goalnfr);
					if(goalDemand.get(goalname)!=null)
					{
						String fetched=goalDemand.get(goalname);
						fetched=fetched+","+nfrname;
						goalDemand.put(goalname, fetched);
					}
					else
					{
						
						goalDemand.put(goalname, nfrname);
					}
				}
				
				line=br.readLine();
				goalname=null;
				nfrname=null;
			}
			String nfr="";
			for (Map.Entry<String,String> entry : goalDemand.entrySet())
			{
				System.out.println(entry.getKey()+" demands "+entry.getValue());
				stmt.executeUpdate("update fsmdata2 set demands='"+entry.getValue()+"' where goal='"+entry.getKey()+"' ");
			}
			con.close();
			
		}
		catch(Exception e)
		{
			System.out.println(e.toString());
		}
	}
	
}
