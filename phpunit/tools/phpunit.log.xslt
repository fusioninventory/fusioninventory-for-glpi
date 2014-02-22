<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:param name='projectname' select='NONAME'/>

  <xsl:output method="html" encoding="utf-8" />

  <xsl:template match="testcase">
    <li>
      <span class='result'>
        <xsl:value-of select="@name"/>
        <xsl:choose>
          <xsl:when test="failure">
            <span class='failed'> failed !</span>
            <pre>
              <xsl:value-of select="*"/>
            </pre>
          </xsl:when>
          <xsl:when test="error">
            <xsl:apply-templates select='error' />
          </xsl:when>
          <xsl:otherwise>
            <span class='passed'> passed </span>
          </xsl:otherwise>
        </xsl:choose>
      </span>
    </li>
  </xsl:template>

  <xsl:template match="error">
    <xsl:choose>
      <xsl:when test="@type = 'PHPUnit_Framework_IncompleteTestError'">
        <span class='notimplemented'> is incomplete !</span>
        <pre>
          <xsl:value-of select="text()" />
        </pre>
      </xsl:when>
      <xsl:otherwise>
        <span class='failed'> is in error ! (<xsl:value-of select="@type" />)</span>
        <pre>
          <xsl:value-of select="text()" />
        </pre>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

  <xsl:template match="testsuites">
    <ul class='testsuite'>
    <xsl:apply-templates select="testsuite" />
    </ul>
  </xsl:template>

  <xsl:template match="testsuite">
      <li>
        <h2>
          <a>
            <xsl:attribute name='id'>
              <xsl:text disable-output-escaping="yes">testsuite</xsl:text><xsl:number level="multiple" format="1.1"/>
            </xsl:attribute>
            Testsuite <xsl:value-of select="@name"/>
          </a>
          <div class='info'>
            <ul>
              <li>Assertions : <xsl:value-of select='@assertions'/></li>
              <li>Failures : <xsl:value-of select='@failures'/></li>
              <li>Errors : <xsl:value-of select='@errors'/></li>
              <li>Duration : <xsl:value-of select='@time'/> seconds</li>
            </ul>
          </div>
        </h2>
        <xsl:choose>
          <xsl:when test="testcase">
            <ul>
              <xsl:apply-templates select="testcase" />
            </ul>
          </xsl:when>
          <xsl:otherwise>
            <ul class='testsuite'>
              <xsl:apply-templates select="testsuite" />
            </ul>
          </xsl:otherwise>
        </xsl:choose>
      </li>
  </xsl:template>

  <xsl:template match="/">
    <xsl:text disable-output-escaping='yes'>&lt;!DOCTYPE html&gt;</xsl:text>
    <html>
      <head>
        <link rel="icon" href="http://www.fusioninventory.org/favicon.ico" type="image/x-icon"/>
        <style>
          body { font-size: 14pt; color: black; background-color: white }

          h1 {font-size: 1.5em }

          h2 { font-size: 1.0em; }

          .result { font-size:0.8em}

          .failed { font-weight:bolder; color : red; }

          .notimplemented { font-weight:bolder; color : darkorange; }

          .passed { font-weight:bolder; color : green; }

          .info ul { margin:0; font-size:10pt; }

          .info ul li { display:inline;
          font-family:monospace;
          padding-right:0.5em
          }

          li {font-size:1em}

          .larger { font-size: 1.2em }

          .testsuite { color: black }

          .info ul li:before {
          content:'\25C2';
          margin-right:2px;
          }
          .info ul li:after {
          content:'\25B8';
          margin-left:2px;
          margin-rigth:1em;
          }

          .summary {
          background-color: #F7F7F7;
          }

          ul.testsuite {
          list-style:none;
          margin-left:0;
          padding-left: 0.5em;
          }

          body > ul.testsuite {
          padding:0;
          }

          body > ul.testsuite > li > h2{
          font-size: 1.5em;
          background-color: #BBB;
          }
          body > ul.testsuite > li {
          background-color: #F7F7F7;
          }

        </style>
        <title>Project <xsl:value-of select="$projectname" /></title>
      </head>
      <body>
        <h1>Project <xsl:value-of select="$projectname" /></h1>
        <h2>Testsuites Summary</h2>
        <div class='info larger summary'>
          <xsl:for-each select="testsuites/testsuite">
            <div class="testsuite">
              <a>
                <xsl:attribute name='href'>
                  <xsl:text disable-output-escaping="yes">#testsuite</xsl:text><xsl:number level="multiple" format="1.1"/>
                </xsl:attribute>
                <xsl:number level="multiple" format="1"/> - Testsuite <xsl:value-of select="@name"/>
              </a>
            </div>
            <ul>
              <xsl:variable name='nbtests_runs' select="count(.//testcase)"/>
              <xsl:variable name='nbtests_errors' select="count(.//error)"/>
              <xsl:variable name='nbtests_fails' select="count(.//failure)"/>
              <li class='normal'>Testcases : <xsl:value-of select="$nbtests_runs" /></li>
              <li class='passed'>Passed : <xsl:value-of select="$nbtests_runs - $nbtests_errors - $nbtests_fails" /></li>
              <xsl:if test="$nbtests_errors > 0" >
                <li class='failed'>
                  <xsl:text>Errors : </xsl:text><xsl:value-of select="$nbtests_errors" />
                </li>
              </xsl:if>
              <xsl:if test="$nbtests_fails > 0" >
                <li class='failed'>
                  <xsl:text>Failures : </xsl:text><xsl:value-of select="$nbtests_fails" />
                </li>
              </xsl:if>
            </ul>
          </xsl:for-each>
        </div>

        <xsl:apply-templates select="testsuites" />
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
