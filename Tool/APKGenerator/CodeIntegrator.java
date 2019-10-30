package AcceleoFsm2Code.main;
import java.io.*;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;
import java.util.*;

class ReadAndParse
{
	String fnumber="";
	ArrayList<String>functionNameList;
	int isparallel=0;
	HashMap<String,String>variablemap;
	String token="",token2="",returnType="",functionName="",variable="",dataType="",validSignature="";
	public ReadAndParse()
	{
		variablemap=new HashMap<>();
	}
	public void parse(String filename)
	{
		try
		{
			functionNameList=new ArrayList<String>();
			BufferedReader br=new BufferedReader(new FileReader(filename));
			String line=br.readLine();
			while(line!=null)
			{
				variable="";
				StringTokenizer st=new StringTokenizer(line, ">>");
				while(st.hasMoreTokens())
				{
					 token=st.nextToken();
					//System.out.println(st.nextToken());
					StringTokenizer st2=new StringTokenizer(token);
					while(st2.hasMoreTokens())
					{
						token2=st2.nextToken();
						//System.out.println(token2);
						if(token2.equalsIgnoreCase("ReturnType:"))
						{
							returnType=st2.nextToken();
							//System.out.println(returnType);
						}
						else if(token2.equalsIgnoreCase("FunctionName:"))
						{
							functionName=st2.nextToken();
							functionName=returnType+" "+functionName;
							//System.out.println(functionName);
							
							validSignature=searchVariable(functionName,variable,dataType);
							
							if(validSignature!=null)
							{
								validSignature=validSignature.replaceAll("#","<");
								validSignature=validSignature.replaceAll("-", ">");
								functionNameList.add(validSignature); // function having variable passed as param.
								System.out.println(validSignature);
							}
							
							else
							{
								System.out.println(functionName+" has no variable param");
								functionName=functionName.replaceAll("#","<");
								functionName=functionName.replaceAll("-", ">");
								functionNameList.add(functionName);   // Function having no variable passed as parameter 
								System.out.println(functionName+" has no variable param");
							}
							//System.out.println("Function name:"+st2.nextToken());
						}
						else if(token2.equalsIgnoreCase("Variable:"))
						{
							variable=st2.nextToken();
							//System.out.println("return type is "+returnType);
							dataType=findreturnType(returnType);
							variablemap.put(variable, dataType);
							
						}
					}
				}
				line=br.readLine();
			}
			SearchMapTable(functionNameList);
		}
		catch(Exception e)
		{
			System.err.println(e.toString());
		}
	}
	private void SearchMapTable(ArrayList<String> functionNameList2) {
		System.out.println("\nSearching the signature in the Mapping Table\n=======================================================\n\n");
		Connection con=null;
		ResultSet rs=null;
		 String driverfunc="";
		try
		{
			String drivername="com.microsoft.sqlserver.jdbc.SQLServerDriver";
			Class.forName(drivername);
			String db="jdbc:sqlserver://localhost:1433;user=sa;password=cmsa019;databaseName=GRL2APK";
			con=DriverManager.getConnection(db);
			//System.out.println("Driver loaded successfully");
	           Statement stmt=con.createStatement();
	           int i=1;
	          
	           for(String s:functionNameList2)
	   			{
	        	   System.out.println(s);
	        	    rs=stmt.executeQuery("select fid from functionMapping where signature='"+s+"'");
	   				//System.out.println(s);
	        	    while(rs.next())
	 	           	{
	        	    	System.out.println(rs.getString(1));
	        	    	//genCode(rs.getString(1));
	        	    	S3Sample aws=new S3Sample();
	        	    	aws.downloadNfr(rs.getString(1));
	 	           
	 	           	}
	        	    
	   			}
	          /* rs=stmt.executeQuery("select func from functionImp where fid='"+driverfunc+"'");
	           String func="";
	           while(rs.next())
	           	{
	        	    func=rs.getString(1);
	        	   
	           	}*/
	           //genCode(func);
	           if(isparallel==1)
	           {
	        	   System.out.println("This functions will be executed in parallel");
	           }
	           else
	           {
	        	   System.out.println("This functions will be executed sequentially");
	           }
	        System.out.println("The code corresponding to these ids will be downloaded from aws and function call will be placed");   
	         // call AWS();
	        generateCode(functionNameList2);
	        genCode("Hello");
		}
		catch(Exception e)
		{
			System.out.println(e.toString());
		}

		
		/*for(String s:functionNameList2)
		{
			System.out.println(s);
		}*/
		
	}
	
	private String searchVariable(String functionName2, String variable2, String rettype)
	{
		if(variable.equals(""))
		{
			return null;
		}
		if(variable2.equals(""))
		{
			return functionName2;
		}
		
		String actualfunname=functionName2;
		String pattern1=","+variable2;
		String pattern2=variable2+",";
		String pattern3="("+variable2+")";
		String replace1=","+rettype;
		String replace2=rettype+",";
		String replace3="("+rettype+")";
		if(functionName2.contains(pattern1))
		{
			actualfunname=functionName2.replace(pattern1, replace1);
		}
		else if(functionName2.contains(pattern2))
		{
			actualfunname=functionName2.replace(pattern2, replace2);
		}
		else if(functionName2.contains(pattern3))
		{
			actualfunname=functionName2.replace(pattern3, replace3);
		}
		
		
		return actualfunname;
	}
	private String findreturnType(String returnType2) 
	{
		String dt="";
		//System.out.println(returnType2);
		switch(returnType)
		{
			case "int":
				//System.out.println("this is Integer");
				dt="int";
				break;
			case "String":
					dt="String";
					//System.out.println("This is String");
					break;
			case "double":
					dt="double";
					break;
			case "ArrayList#String-":
					dt="ArrayList<String>";
					break;
			default: 
				//System.out.println("This is arrayList");
		}
		return dt;
	}
	public void genCode(String f)
	{
		Connection connection=null;
		FileWriter fw=null;
		try
		{
			String code="";
			
			
			String funcName=fnumber;
				String drivername="com.microsoft.sqlserver.jdbc.SQLServerDriver";
				Class.forName(drivername);
				String db="jdbc:sqlserver://localhost:1433;user=sa;password=password;databaseName=GRL2APK";
				connection=DriverManager.getConnection(db);
				//System.out.println("Driver loaded successfully");
		           Statement stmt1=connection.createStatement();
		           ResultSet rs1=stmt1.executeQuery("select func from functionImp where fid='"+funcName+"'");
		           while(rs1.next())
		           {
		        	   code=rs1.getString(1);
		        	   
		           }
			fw=new FileWriter("E:/generatedCode/Driver.java");
			fw.write(code);
			fw.close();
			connection.close();
			
		}
		catch(Exception e)
		{
			
		}
	}
	public void parseParallel(String filename)
	{
		try
		{
			functionNameList=new ArrayList<String>();
			BufferedReader br=new BufferedReader(new FileReader(filename));
			String line=br.readLine();
			while(line!=null)
			{
				StringTokenizer st=new StringTokenizer(line, "|");
				while(st.hasMoreTokens())
				{
					 token=st.nextToken();
					//System.out.println(st.nextToken());
					StringTokenizer st2=new StringTokenizer(token);
					while(st2.hasMoreTokens())
					{
						token2=st2.nextToken();
						//System.out.println(token2);
						if(token2.equalsIgnoreCase("ReturnType:"))
						{
							returnType=st2.nextToken();
							//System.out.println(returnType);
						}
						else if(token2.equalsIgnoreCase("FunctionName:"))
						{
							functionName=st2.nextToken();
							functionName=returnType+" "+functionName;
							System.out.println(functionName);
							
							validSignature=searchVariable(functionName,variable,dataType);
							if(validSignature!=null)
							{
								validSignature=validSignature.replaceAll("#","<");
								validSignature=validSignature.replaceAll("-", ">");
								functionNameList.add(validSignature); // function having variable passed as param.
								System.out.println(validSignature);
							}
							else
							{
								functionName=functionName.replaceAll("#","<");
								functionName=functionName.replaceAll("-", ">");
								functionNameList.add(functionName);   // Function having no variable passed as parameter 
								System.out.println(functionName+" has no variable param");
							}
							//System.out.println("Function name:"+st2.nextToken());
						}
						else if(token2.equalsIgnoreCase("Variable:"))
						{
							variable=st2.nextToken();
							//System.out.println("return type is "+returnType);
							dataType=findreturnType(returnType);
							
							
						}
					}
				}
				line=br.readLine();
			}
			isparallel=1;
			SearchMapTable(functionNameList);
		}
		catch(Exception e)
		{
			System.err.println(e.toString());
		}
	}
	public void modifiedParse(String filename)
	{
		ArrayList<String>functionsignaturelist=new ArrayList<>();
		String[]siglist;
		try
		{
			FileReader fr=new FileReader(filename);
			BufferedReader br=new BufferedReader(fr);
			String line=br.readLine();
			System.out.println(line);
			fnumber=line;
			line=br.readLine();
			System.out.println(line);
			siglist=line.split(">>");
			for(String s:siglist)
			{
				
				s=s.trim();
				//System.out.println(s);
				functionsignaturelist.add(s);
			}
//			while(line!=null)
//			{
//				
//				
//				StringTokenizer st=new StringTokenizer(line, "seq");
//				while(st.hasMoreTokens())
//				{
//					
//					String token=st.nextToken();
//					//System.out.println(token);
//					
//					token=token.trim();
//					functionsignaturelist.add(token);
//					
//				}
//				line=br.readLine();
//			}
//			
			SearchMapTable(functionsignaturelist);
		}
		catch(Exception e)
		{
			
		}
	}
}
public class Parse {

	
	public static void main(String[] args) {
		ReadAndParse rp=new ReadAndParse();
		//rp.parse("E:\\workflow2code\\workflow2.txt");
		//rp.parseParallel("E:/workflowp.wf");
		rp.modifiedParse("../workflow.txt");
	}

}
