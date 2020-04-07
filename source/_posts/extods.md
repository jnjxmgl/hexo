---
title: 将EXCEL中的数据转换成DATASET
date: 2019-07-14 15:10:14
tags: C#
categories: 
- windows
- .NET
keywords:
- GetDataSet
- Microsoft.ACE.OLEDB.12.0
- OleDbConnection
- OleDbDataAdapter
- Exception

---

```C#
/// <summary>

    /// EXCEL数据转换DataSet
    /// </summary>
    /// <param name="filePath">文件全路径</param>
    /// <param name="search">表名</param>
    /// <returns></returns>       
    private DataSet GetDataSet(string fileName)
    {
        string strConn = "Provider=Microsoft.ACE.OLEDB.12.0;Data Source=" + fileName + ";Extended Properties='Excel 12.0;HDR=Yes;IMEX=1';";
        OleDbConnection objConn = null;
        objConn = new OleDbConnection(strConn);
        objConn.Open();
        DataSet ds = new DataSet();
        //List<string> List = new List<string> { "收款金额", "代付关税", "垫付费用", "超期", "到账利润" };
        List<string> List = new List<string> { };
        DataTable dtSheetName = objConn.GetOleDbSchemaTable(OleDbSchemaGuid.Tables, new object[] { null, null, null, "TABLE" });
        foreach (DataRow dr in dtSheetName.Rows)
        {
            if (dr["Table_Name"].ToString().Contains("$") && !dr[2].ToString().EndsWith("$"))
            {
                continue;
            }
            string s = dr["Table_Name"].ToString();
            List.Add(s);
        }
        try
        {
            for (int i = 0; i < List.Count; i++)
            {
                ds.Tables.Add(List[i]);
                string SheetName = List[i];
                string strSql = "select * from [" + SheetName + "]";
                OleDbDataAdapter odbcCSVDataAdapter = new OleDbDataAdapter(strSql, objConn);
                DataTable dt = ds.Tables[i];
                odbcCSVDataAdapter.Fill(dt);
            }
            return ds;
        }
        catch (Exception ex)
        {
            return null;
        }
        finally
        {
            objConn.Close();
            objConn.Dispose();
        }
    }

```

